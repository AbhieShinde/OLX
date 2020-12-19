<?php
namespace Olx\controllers\Tools;

use Olx\controllers\CBaseController;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class CAwsS3ClientController extends CBaseController {

    private $m_strBucketName = 'abhieshinde-olx';
    protected $m_objS3Client;

    public function __construct() {

        $this->m_objS3Client = new S3Client([
            'version' => 'latest',
            'region'  => 'ap-south-1',
            'credentials' => [
                'key'    => $_ENV['AWS_ACCESS_KEY_ID'],
                'secret' => $_ENV['AWS_ACCESS_KEY_SECRET'],
            ]
        ]);
    }

    public function uploadFile( $resUploadedFile ) {

        $strName = $resUploadedFile->getClientFilename();

        $strExtension = pathinfo($strName, PATHINFO_EXTENSION);
        $strBasename = pathinfo($strName, PATHINFO_FILENAME) . '_' . bin2hex(random_bytes(5));
        $strFileName = sprintf('%s.%s', $strBasename, $strExtension);
        $strKey = $_SESSION['user'] . '/' . $strFileName;

        try {
                            
            $objResult = $this->m_objS3Client->putObject([
                'Bucket' => $this->m_strBucketName,
                'Key'    => $strKey,
                'Body'   => fopen( $resUploadedFile->file, 'r'),
                'ACL'    => 'public-read',
            ]);
            
            return ( 200 == $objResult->get('@metadata')['statusCode'] ) ? $objResult->get('ObjectURL') : 'ERROR';
        
        } catch ( S3Exception $objError ) {

            echo $objError->getMessage();
            return 'ERROR';
        }
    }

    public function deleteFile( $strKey ) {

        try {

            $this->m_objS3Client->deleteObject([
                'Bucket' => $this->m_strBucketName,
                'Key'    => $strKey
            ]);

            return true;
        
        } catch ( S3Exception $objError ) {

            echo $objError->getMessage();
            return false;
        }
    }
}