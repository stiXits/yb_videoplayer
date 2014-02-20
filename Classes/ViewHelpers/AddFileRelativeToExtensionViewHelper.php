<?php
namespace TYPO3\YbVideoplayer\ViewHelpers;
/**
* A view helper for getting a specific date as "how along ago"
*
*
*/
class AddFileRelativeToExtensionViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	*
	* @param string $extensionKey
	* @param string $pathToJsFile
	* @return void
	*/
	public function render($extensionKey = NULL, $pathToFile = NULL) {
		
		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($pathToFile);
		if (NULL === $extensionKey) {
			$extensionKey =	$this->controllerContext->getRequest()->getControllerExtensionKey();
		}

		if (NULL === $pathToFile) {
			$pathToJsFile = $this->renderChildren();
		}

		$fileType = substr(strrchr($pathToFile,'.'), 1);

		$pageRenderer = $this->getDocInstance()->getPageRenderer();



		/* @var $pageRenderer t3lib_PageRenderer */
		if($fileType == 'js') {
			$pageRenderer->addJsFile($pathToFile);
		} else if($fileType = 'css') {
			$pageRenderer->addCssFile($pathToFile);
		}
		
		return $pathToFile;
	}
}

?>
