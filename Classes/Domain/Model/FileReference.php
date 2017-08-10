<?php namespace TYPO3\YbVideoplayer\Domain\Model;

class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference {

    protected $uidLocal;
    protected $tableLocal;
    protected $tablenames;
    protected $title;

    /**
     * @param \TYPO3\YbVideoplayer\Domain\Model\Video
     * @param \TYPO3\CMS\Core\Resource\FileInterface $originalResource
     */
    public function setOriginalResource(\TYPO3\YbVideoplayer\Domain\Model\Video &$video, \TYPO3\CMS\Core\Resource\FileInterface &$originalResource, $fieldname = 'file', $resolution = "default") {
	$objectManager = $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\ExtBase\Object\ObjectManager');
	$fileFactory = $objectManager->get('\TYPO3\CMS\Core\Resource\ResourceFactory');

        $dataArray = array(
        	'uid_local' => $originalResource->getUid(),
		'table_local' => 'sys_file',
                'tablenames' => 'tx_ybvideoplayer_domain_model_video',
                'uid_foreign' => $video->getUid(),
		'pid' => $video->getPid(),
		'fieldname' => $fieldname,
		'title' => $resolution
                );

	$this->tableLocal = 'sys_file';
	$this->tablenames = 'tx_ybvideoplayer_domain_model_video';
	$this->title = $resolution;

	//\t3lib_div::devLog('Isert filereference:', 'yb_videoplayer', 1, array($dataArray, $res));

	$fileReference = $fileFactory->getFileReferenceObject($originalResource->getUid(), $dataArray);
        $this->originalResource = $fileReference;
        $this->uidLocal = $fileReference->getOriginalFile()->getUid();
    }

}
?>
