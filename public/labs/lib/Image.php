<?php
/**
 * Image
 * 
 * @author Rob Keplin
 * @link http://www.robkeplin.com
 **/

class Image
{
    /**
     * @var string 
     */
    private $_url;
    
    /**
     * @var mixed {boolean, image resource} 
     */
    private $_image;
    
    /**
     * @var int
     */
    private $_quality;
    
    /**
     * @var array
     */
    private $_errors;

    /**
     * Constructor
     * 
     * @param string $url
     */
    public function __construct($url, $quality = 100)
    {
        $this->_errors = array();
        $this->_image = false;
        $this->_quality = $quality;
        $this->_url = $url;
        
        if(!$this->_setResource()) {
            $this->_errors[] = 'Unable to set image resource.';
        }
    }
    
    /**
     * Sets the image resource
     * 
     * @param void
     * @return void
     */
    protected function _setResource()
    {
        $ext = strrchr($this->_url, '.');
        $ext = strtolower($ext);

        switch($ext) {
            case '.jpeg':
            case '.jpg':
                $this->_image = imagecreatefromjpeg($this->_url);
                break;
            case '.gif':
                $this->_image = imagecreatefromgif($this->_url);
                break;
            case '.png':
                $this->_image = imagecreatefrompng($this->_url);
                break;
        }
        
        if($this->_image === false) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Resizes the image resource
     * 
     * @param int $new_width
     * @param int $new_height 
     * @return void
     */
    public function resize($new_width, $new_height)
    {
        if(!$this->_image) {
            throw new Exception('The image resource is not set up.');
        }
        
        $old_width = imagesx($this->_image);
        $old_height = imagesy($this->_image);

        //Keep the same resolution of original image
        if($old_width > $old_height) {
            $ratio = $old_height / $new_height;
        } else {
            $ratio = $old_width / $new_width;
        }

        $new_width = ceil($old_width / $ratio);
        $new_height = ceil($old_height / $ratio);

        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $this->_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
        
        $this->_image = $new_image;
    }
    
    /**
     * Displays the image resource
     * 
     * @param void
     * @return string
     * @throws Exception
     */
    public function display()
    {
        if(!$this->_image) {
            throw new Exception('The image resource is not set up.');
        }
        
        header('Content-type: image/jpeg');
        imagejpeg($this->_image, null, $this->_quality);
        imagedestroy($this->_image);
    }
    
    /**
     * Gets errors
     * 
     * @param void
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }
}