<?php
/**
 * @package    Joomla.Platform
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

use Joomla\Registry\Registry;

/**
 * Joomla Platform Factory class.
 *
 * @since  11.1
 */
abstract class JFactory
{
	/**
	 * Global application object
	 *
	 * @var    JApplicationCms
	 * @since  11.1
	 */
	public static $application = null;

	/**
	 * Global cache object
	 *
	 * @var    JCache
	 * @since  11.1
	 */
	public static $cache = null;

	/**
	 * Global configuraiton object
	 *
	 * @var    JConfig
	 * @since  11.1
	 */
	public static $config = null;

	/**
	 * Container for JDate instances
	 *
	 * @var    array
	 * @since  11.3
	 */
	public static $dates = array();

	/**
	 * Global session object
	 *
	 * @var    JSession
	 * @since  11.1
	 */
	public static $session = null;

	/**
	 * Global language object
	 *
	 * @var    JLanguage
	 * @since  11.1
	 */
	public static $language = null;

	/**
	 * Global document object
	 *
	 * @var    JDocument
	 * @since  11.1
	 */
	public static $document = null;

	/**
	 * Global ACL object
	 *
	 * @var    JAccess
	 * @since  11.1
	 * @deprecated  13.3 (Platform) & 4.0 (CMS)
	 */
	public static $acl = null;

	/**
	 * Global database object
	 *
	 * @var    JDatabaseDriver
	 * @since  11.1
	 */
	public static $database = null;

	/**
	 * Global mailer object
	 *
	 * @var    JMail
	 * @since  11.1
	 */
	public static $mailer = null;

	/**
	 * Get a application object.
	 *
	 * Returns the global {@link JApplicationCms} object, only creating it if it doesn't already exist.
	 *
	 * @param   mixed   $id      A client identifier or name.
	 * @param   array   $config  An optional associative array of configuration settings.
	 * @param   string  $prefix  Application prefix
	 *
	 * @return  JApplicationCms object
	 *
	 * @see     JApplication
	 * @since   11.1
	 * @throws  Exception
	 */
	public static function getApplication($id = null, array $config = array(), $prefix = 'J')
	{
		if (!self::$application)
		{
			if (!$id)
			{
				throw new Exception('Application Instantiation Error', 500);
			}

			self::$application = JApplicationCms::getInstance($id);
		}

		return self::$application;
	}

	/**
	 * Get a configuration object
	 *
	 * Returns the global {@link JConfig} object, only creating it if it doesn't already exist.
	 *
	 * @param   string  $file       The path to the configuration file
	 * @param   string  $type       The type of the configuration file
	 * @param   string  $namespace  The namespace of the configuration file
	 *
	 * @return  Registry
	 *
	 * @see     Registry
	 * @since   11.1
	 */
	public static function getConfig($file = null, $type = 'PHP', $namespace = '')
	{
		return self::$application->getConfiguration();
	}

	/**
	 * Get a session object.
	 *
	 * Returns the global {@link JSession} object, only creating it if it doesn't already exist.
	 *
	 * @param   array  $options  An array containing session options
	 *
	 * @return  JSession object
	 *
	 * @see     JSession
	 * @since   11.1
	 */
	public static function getSession(array $options = array())
	{
		return self::$application->getSession();
	}

	/**
	 * Get a language object.
	 *
	 * Returns the global {@link JLanguage} object, only creating it if it doesn't already exist.
	 *
	 * @return  JLanguage object
	 *
	 * @see     JLanguage
	 * @since   11.1
	 */
	public static function getLanguage()
	{
		return self::$application->getLanguage();
	}

	/**
	 * Get a document object.
	 *
	 * Returns the global {@link JDocument} object, only creating it if it doesn't already exist.
	 *
	 * @return  JDocument object
	 *
	 * @see     JDocument
	 * @since   11.1
	 */
	public static function getDocument()
	{
		return self::$application->getDocument();
	}

	/**
	 * Get an user object.
	 *
	 * Returns the global {@link JUser} object, only creating it if it doesn't already exist.
	 *
	 * @param   integer  $id  The user to load - Can be an integer or string - If string, it is converted to ID automatically.
	 *
	 * @return  JUser object
	 *
	 * @see     JUser
	 * @since   11.1
	 */
	public static function getUser($id = null)
	{
		$instance = self::getSession()->get('user');

		if (is_null($id))
		{
			if (!($instance instanceof JUser))
			{
				$instance = JUser::getInstance();
			}
		}
		// Check if we have a string as the id or if the numeric id is the current instance
		elseif (!($instance instanceof JUser) || is_string($id) || $instance->id !== $id)
		{
			$instance = JUser::getInstance($id);
		}

		return $instance;
	}

	/**
	 * Get a cache object
	 *
	 * Returns the global {@link JCache} object
	 *
	 * @param   string  $group    The cache group name
	 * @param   string  $handler  The handler to use
	 * @param   string  $storage  The storage method
	 *
	 * @return  JCacheController object
	 *
	 * @see     JCache
	 * @since   11.1
	 */
	public static function getCache($group = '', $handler = 'callback', $storage = null)
	{
		$hash = md5($group . $handler . $storage);

		if (isset(self::$cache[$hash]))
		{
			return self::$cache[$hash];
		}

		$handler = ($handler == 'function') ? 'callback' : $handler;

		$options = array('defaultgroup' => $group);

		if (isset($storage))
		{
			$options['storage'] = $storage;
		}

		$cache = JCache::getInstance($handler, $options);

		self::$cache[$hash] = $cache;

		return self::$cache[$hash];
	}

	/**
	 * Get an authorization object
	 *
	 * Returns the global {@link JAccess} object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  JAccess object
	 *
	 * @deprecated  13.3 (Platform) & 4.0 (CMS) - Use JAccess directly.
	 */
	public static function getAcl()
	{
		JLog::add(__METHOD__ . ' is deprecated. Use JAccess directly.', JLog::WARNING, 'deprecated');

		if (!self::$acl)
		{
			self::$acl = new JAccess;
		}

		return self::$acl;
	}

	/**
	 * Get a database object.
	 *
	 * Returns the global {@link JDatabaseDriver} object, only creating it if it doesn't already exist.
	 *
	 * @return  JDatabaseDriver
	 *
	 * @see     JDatabaseDriver
	 * @since   11.1
	 */
	public static function getDbo()
	{
		// TODO where should the database exactly being attached to?
		return self::$application->getContainer()->get('dbo');
	}

	/**
	 * Get a mailer object.
	 *
	 * Returns the global {@link JMail} object, only creating it if it doesn't already exist.
	 *
	 * @return  JMail object
	 *
	 * @see     JMail
	 * @since   11.1
	 */
	public static function getMailer()
	{
		if (!self::$mailer)
		{
			self::$mailer = self::createMailer();
		}

		$copy = clone self::$mailer;

		return $copy;
	}

	/**
	 * Get a parsed XML Feed Source
	 *
	 * @param   string   $url         Url for feed source.
	 * @param   integer  $cache_time  Time to cache feed for (using internal cache mechanism).
	 *
	 * @return  mixed  SimplePie parsed object on success, false on failure.
	 *
	 * @since   11.1
	 * @throws  BadMethodCallException
	 * @deprecated  4.0  Use directly JFeedFactory or supply SimplePie instead. Mehod will be proxied to JFeedFactory beginning in 3.2
	 */
	public static function getFeedParser($url, $cache_time = 0)
	{
		if (!class_exists('JSimplepieFactory'))
		{
			throw new BadMethodCallException('JSimplepieFactory not found');
		}

		JLog::add(__METHOD__ . ' is deprecated.   Use JFeedFactory() or supply SimplePie instead.', JLog::WARNING, 'deprecated');

		return JSimplepieFactory::getFeedParser($url, $cache_time);
	}

	/**
	 * Reads a XML file.
	 *
	 * @param   string   $data    Full path and file name.
	 * @param   boolean  $isFile  true to load a file or false to load a string.
	 *
	 * @return  mixed    JXMLElement or SimpleXMLElement on success or false on error.
	 *
	 * @see     JXMLElement
	 * @since   11.1
	 * @note    When JXMLElement is not present a SimpleXMLElement will be returned.
	 * @deprecated  13.3 (Platform) & 4.0 (CMS) - Use SimpleXML directly.
	 */
	public static function getXml($data, $isFile = true)
	{
		JLog::add(__METHOD__ . ' is deprecated. Use SimpleXML directly.', JLog::WARNING, 'deprecated');

		$class = 'SimpleXMLElement';

		if (class_exists('JXMLElement'))
		{
			$class = 'JXMLElement';
		}

		// Disable libxml errors and allow to fetch error information as needed
		libxml_use_internal_errors(true);

		if ($isFile)
		{
			// Try to load the XML file
			$xml = simplexml_load_file($data, $class);
		}
		else
		{
			// Try to load the XML string
			$xml = simplexml_load_string($data, $class);
		}

		if ($xml === false)
		{
			JLog::add(JText::_('JLIB_UTIL_ERROR_XML_LOAD'), JLog::WARNING, 'jerror');

			if ($isFile)
			{
				JLog::add($data, JLog::WARNING, 'jerror');
			}

			foreach (libxml_get_errors() as $error)
			{
				JLog::add($error->message, JLog::WARNING, 'jerror');
			}
		}

		return $xml;
	}

	/**
	 * Get an editor object.
	 *
	 * @param   string  $editor  The editor to load, depends on the editor plugins that are installed
	 *
	 * @return  JEditor instance of JEditor
	 *
	 * @since   11.1
	 * @throws  BadMethodCallException
	 * @deprecated 12.3 (Platform) & 4.0 (CMS) - Use JEditor directly
	 */
	public static function getEditor($editor = null)
	{
		JLog::add(__METHOD__ . ' is deprecated. Use JEditor directly.', JLog::WARNING, 'deprecated');

		if (!class_exists('JEditor'))
		{
			throw new BadMethodCallException('JEditor not found');
		}

		// Get the editor configuration setting
		if (is_null($editor))
		{
			$conf = self::getConfig();
			$editor = $conf->get('editor');
		}

		return JEditor::getInstance($editor);
	}

	/**
	 * Return a reference to the {@link JUri} object
	 *
	 * @param   string  $uri  Uri name.
	 *
	 * @return  JUri object
	 *
	 * @see     JUri
	 * @since   11.1
	 * @deprecated  13.3 (Platform) & 4.0 (CMS) - Use JUri directly.
	 */
	public static function getUri($uri = 'SERVER')
	{
		JLog::add(__METHOD__ . ' is deprecated. Use JUri directly.', JLog::WARNING, 'deprecated');

		return JUri::getInstance($uri);
	}

	/**
	 * Return the {@link JDate} object
	 *
	 * @param   mixed  $time      The initial time for the JDate object
	 * @param   mixed  $tzOffset  The timezone offset.
	 *
	 * @return  JDate object
	 *
	 * @see     JDate
	 * @since   11.1
	 */
	public static function getDate($time = 'now', $tzOffset = null)
	{
		static $classname;
		static $mainLocale;

		$language = self::getLanguage();
		$locale = $language->getTag();

		if (!isset($classname) || $locale != $mainLocale)
		{
			// Store the locale for future reference
			$mainLocale = $locale;

			if ($mainLocale !== false)
			{
				$classname = str_replace('-', '_', $mainLocale) . 'Date';

				if (!class_exists($classname))
				{
					// The class does not exist, default to JDate
					$classname = 'JDate';
				}
			}
			else
			{
				// No tag, so default to JDate
				$classname = 'JDate';
			}
		}

		$key = $time . '-' . ($tzOffset instanceof DateTimeZone ? $tzOffset->getName() : (string) $tzOffset);

		if (!isset(self::$dates[$classname][$key]))
		{
			self::$dates[$classname][$key] = new $classname($time, $tzOffset);
		}

		$date = clone self::$dates[$classname][$key];

		return $date;
	}

	/**
	 * Create a mailer object
	 *
	 * @return  JMail object
	 *
	 * @see     JMail
	 * @since   11.1
	 */
	protected static function createMailer()
	{
		$conf = self::getConfig();

		$smtpauth = ($conf->get('smtpauth') == 0) ? null : 1;
		$smtpuser = $conf->get('smtpuser');
		$smtppass = $conf->get('smtppass');
		$smtphost = $conf->get('smtphost');
		$smtpsecure = $conf->get('smtpsecure');
		$smtpport = $conf->get('smtpport');
		$mailfrom = $conf->get('mailfrom');
		$fromname = $conf->get('fromname');
		$mailer = $conf->get('mailer');

		// Create a JMail object
		$mail = JMail::getInstance();

		// Set default sender without Reply-to
		$mail->SetFrom(JMailHelper::cleanLine($mailfrom), JMailHelper::cleanLine($fromname), 0);

		// Default mailer is to use PHP's mail function
		switch ($mailer)
		{
			case 'smtp':
				$mail->useSmtp($smtpauth, $smtphost, $smtpuser, $smtppass, $smtpsecure, $smtpport);
				break;

			case 'sendmail':
				$mail->IsSendmail();
				break;

			default:
				$mail->IsMail();
				break;
		}

		return $mail;
	}

	/**
	 * Creates a new stream object with appropriate prefix
	 *
	 * @param   boolean  $use_prefix   Prefix the connections for writing
	 * @param   boolean  $use_network  Use network if available for writing; use false to disable (e.g. FTP, SCP)
	 * @param   string   $ua           UA User agent to use
	 * @param   boolean  $uamask       User agent masking (prefix Mozilla)
	 *
	 * @return  JStream
	 *
	 * @see     JStream
	 * @since   11.1
	 */
	public static function getStream($use_prefix = true, $use_network = true, $ua = null, $uamask = false)
	{
		jimport('joomla.filesystem.stream');

		// Setup the context; Joomla! UA and overwrite
		$context = array();
		$version = new JVersion;

		// Set the UA for HTTP and overwrite for FTP
		$context['http']['user_agent'] = $version->getUserAgent($ua, $uamask);
		$context['ftp']['overwrite'] = true;

		if ($use_prefix)
		{
			$FTPOptions = JClientHelper::getCredentials('ftp');
			$SCPOptions = JClientHelper::getCredentials('scp');

			if ($FTPOptions['enabled'] == 1 && $use_network)
			{
				$prefix = 'ftp://' . $FTPOptions['user'] . ':' . $FTPOptions['pass'] . '@' . $FTPOptions['host'];
				$prefix .= $FTPOptions['port'] ? ':' . $FTPOptions['port'] : '';
				$prefix .= $FTPOptions['root'];
			}
			elseif ($SCPOptions['enabled'] == 1 && $use_network)
			{
				$prefix = 'ssh2.sftp://' . $SCPOptions['user'] . ':' . $SCPOptions['pass'] . '@' . $SCPOptions['host'];
				$prefix .= $SCPOptions['port'] ? ':' . $SCPOptions['port'] : '';
				$prefix .= $SCPOptions['root'];
			}
			else
			{
				$prefix = JPATH_ROOT . '/';
			}

			$retval = new JStream($prefix, JPATH_ROOT, $context);
		}
		else
		{
			$retval = new JStream('', '', $context);
		}

		return $retval;
	}
}
