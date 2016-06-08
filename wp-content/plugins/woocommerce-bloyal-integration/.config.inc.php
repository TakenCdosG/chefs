<?php

define ('DATE_FORMAT', 'Y-m-d\TH:i:s\Z');

/************************************************************************
 * REQUIRED
 *
 * * Connector Key and Api Key, obtained from bLoyal
 *
 * IMPORTANT: The ConnectorKey and ApiKey are only used when generating a new Access Key.
 * Once an AccessKey has been generated you just need it and the clien't login domain.
 * You should treat the AccessKey like a username and password and security store it and
 * never show in an UI.
 ***********************************************************************/
define('BLOYAL_CONNECTOR_KEY', '94ec0683-bca6-42ef-b172-86d11d4b1e56'); // This is your private Connetor key.  Never share or show in a configuration UI.
define('BLOYAL_API_KEY', 'PHLUVWKNCO-POEJEOPWUA'); // <API Key - Provided by client> This is provided by the client and scopes access into their account.

/************************************************************************
 * REQUIRED
 *
 * ApiDemo account information.
 ***********************************************************************/
define('BLOYAL_CLIENT_LOGIN_DOMAIN', 'ChefsEmporium'); //<Your Client Login Domain>
define('BLOYAL_ACCESS_KEY', 'fc434ba4c64e32675f53b1b6f3c64dc4f86aca3c7fad6c35cafc1115b3dfdcbf2ec97442b7ee689bd475458b'); // ApiDemo key.  Go ahead and use for internal API evaluation.
define('BLOYAL_STORECODE', 'Web'); //<Your Client Login Domain>
define('BLOYAL_INVENTORY_TRANSACTION', 'SANDBOX'); //<Your Client Login Domain>
