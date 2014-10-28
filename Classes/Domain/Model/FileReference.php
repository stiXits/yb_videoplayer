<?php namespace TYPO3\YbVideoplayer\Domain\Model;

class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference {

    /**
     * We need this property so that the Extbase persistence can properly persist the object
     *
     * @var integer
     */
    protected $uidLocal;

    protected $tableLocal;
    protected $tablenames;

    /**
     * @param \TYPO3\YbVideoplayer\Domain\Model\Video
     * @param \TYPO3\CMS\Core\Resource\FileInterface $originalResource
     */
    public function setOriginalResource(\TYPO3\YbVideoplayer\Domain\Model\Video &$video, \TYPO3\CMS\Core\Resource\FileInterface &$originalResource) {
	$objectManager = $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\\TYPO3\\CMS\\ExtBase\\Object\\ObjectManager');
	$fileFactory = $objectManager->get('\TYPO3\CMS\Core\Resource\ResourceFactory');

        $dataArray = array(
        	'uid_local' => $originalResource->getUid(),
		'table_local' => 'sys_file',
                'tablenames' => 'tx_ybvideoplayer_domain_model_video',
                'uid_foreign' => $video->getUid(),
		'pid' => $video->getPid(),
		'fieldname' => 'file'
                // the sys_file_reference record should always placed on the same page
                // as the record to link to, see issue #46497
                );

	$this->tableLocal = 'sys_file';
	$this->tablenames = 'tx_ybvideoplayer_domain_model_video';

 //       $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_file_reference', $dataArray);
	\t3lib_div::devLog('Isert filereference:', 'yb_videoplayer', 1, array($dataArray, $res));

	$fileReference = $fileFactory->getFileReferenceObject($originalResource->getUid(), $dataArray);
        $this->originalResource = $fileReference;
        $this->uidLocal = $fileReference->getOriginalFile()->getUid();
    }

}
?>
