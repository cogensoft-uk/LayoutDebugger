<?php

namespace Cogensoft\LayoutDebugger\Model;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Layout  implements ObserverInterface
{
	protected $_logger;
	public function __construct ( \Psr\Log\LoggerInterface $logger
	) {
		$this->_logger = $logger;
	}

	public function execute(Observer $observer)
	{
		$outputFile = BP . '/var/log/current_layout.xml';
		$xmlString = '<?xml version="1.0"?><page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">';
		$xmlString .= $observer->getEvent()->getLayout()->getXmlString();
		$xmlString .= '</page>';

		if(strlen($xmlString) > 2000) {
			$dom = new \DOMDocument;
			$dom->preserveWhiteSpace = FALSE;
			$dom->loadXML($xmlString);
			$dom->formatOutput = TRUE;

			if(file_exists($outputFile)) unlink($outputFile);
			file_put_contents($outputFile, $dom->saveXML());
		}

		return $this;
	}
}