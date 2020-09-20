<?php

class VS7_SwatchesAllImages_Block_Media extends Mage_Core_Block_Template
{
    public function getProduct()
    {
        $product = Mage::registry('product');

        if (!$product) {
            return null;
        }

        return $product;
    }

    public function getJson()
    {
        $data = array();
        $product = $this->getProduct();

        if (empty($product)) {
            return '{}';
        }

        if ($product->hasChildrenProducts()) {
            foreach ($product->getChildrenProducts() as $childProduct) {
                $childProduct->load($childProduct->getId());
                $galleryImages = $childProduct->getMediaGalleryImages();
                foreach ($galleryImages as $galleryImage) {
                    if ($galleryImage->getLabel() != 'stamp') {
                        $data[$childProduct->getId()][] = array(
                            'image' => (string)Mage::helper('catalog/image')->init($childProduct, 'image', $galleryImage->getFile()),
                            'label' => $galleryImage->getLabel()
                        );
                    }
                }
            }

            return Mage::helper('core')->jsonEncode($data);
        }

        return '{}';
    }
}