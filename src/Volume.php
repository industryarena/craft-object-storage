<?php

namespace IndustyArena\CraftObjectStorage;

use Craft;
use Aws\S3\S3Client;
use craft\base\FlysystemVolume;
use Aws\Handler\GuzzleV6\GuzzleHandler;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

// Docs:
// https://flysystem.thephpleague.com/v1/docs/adapter/aws-s3-v3/
// https://github.com/mwikala/linode-s3
// https://github.com/fortrabbit/craft-object-storage
// https://github.com/craftcms/google-cloud

class Volume extends FlysystemVolume
{
    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Craft Object Storage';
    }

    // Properties
    // =========================================================================

    /**
     * @var bool Whether this is a local source or not. Defaults to false.
     */
    protected $isVolumeLocal = false;

    /**
     * @var string Subfolder to use
     */
    public $subfolder = '';

    /**
     * @var string Access Key id
     */
    public $keyId = '';

    /**
     * @var string Secret Key
     */
    public $secret = '';

    /**
     * @var string Linode endpoint
     */
    public $endpoint = '';

    /**
     * @var string Bucket to use
     */
    public $bucket = '';

    /**
     * @var string Region to use
     */
    public $region = '';

    /**
     * @var string Cache expiration period
     */
    public $expires = '';

    /**
     * @var string Content Disposition value
     */
    public $contentDisposition = '';

    /**
     * @var boolean
     */
    public $usepathstyle;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['keyId', 'secret', 'region', 'bucket', 'endpoint'], 'required'];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('craft-object-storage/volumeSettings', [
            'volume' => $this
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getRootUrl()
    {
        if (($rootUrl = parent::getRootUrl()) !== false && $this->getSubfolder()) {
            $rootUrl .= rtrim($this->subfolder, '/');
        }
        return $rootUrl;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     * @return AwsS3Adapter
     */
    protected function createAdapter()
    {
        $config = [
            'version' => 'latest',
            'region' => Craft::parseEnv($this->region),
            'endpoint' => Craft::parseEnv($this->endpoint),
            'use_path_style_endpoint' => Craft::parseEnv($this->usepathstyle) === true,
            'credentials' => [
                'key'    => Craft::parseEnv($this->keyId),
                'secret' => Craft::parseEnv($this->secret),
            ],
            'http_handler' => new GuzzleHandler(Craft::createGuzzleClient())
        ];

        $client = new S3Client($config);

        return new AwsS3Adapter($client, Craft::parseEnv($this->bucket), Craft::parseEnv($this->subfolder));
    }
}
