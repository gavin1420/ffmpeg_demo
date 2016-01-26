<?php
class ffmpeg
{        
        private $videoName = '';
        
        private $tmp_name = '';
        
        private $videoFileName = '';
        
        private $error='';
        
        private $newName = '';

        private $videoNewPith='./video/';
        
        private $imagePith='./image/';
    
        private $type;
        
        private $videoTimeForFrontCover = '8';
        
        private $removeFileName = '';

        public function setVideoNewPith($videoPith)
	{
            $this->$videoNewPith = $videoPith;
	}
        
        public function setImagePith($imagePith)
        {
            $filethis->imagePith = $imagePith;            
        }
        
        public function setNewName($newName)
        {
            $this->newName = $newName;
        }
        
        public function setVideoTimeForFrontCover($vidoeTime)
        {
            $this->videoTimeForFrontCover = $vidoeTime;
        }
        public function setRemoveFileName($name)
        {
            $this->removeFileName = $name;
        }
        
        public function setRemoveFileType($type)
        {
            $this->type = $type;
        }

        public function ffmpeg()
	{
           // echo '<pre>';print_r($_FILES);echo '</pre>';
           if(isset($_FILES['file']))
           {    
                $videoName = explode( '.', $_FILES['file']['name']);
                $this->videoName = $videoName[0];
                $this->videoFileName =  $_FILES['file']['name'];
                $this->type ='.'.$videoName[1];
                $this->tmp_name = $_FILES['file']['tmp_name'];
                $this->error = $_FILES['file']['error'];             
            }
        }
	
        private function uploadVideo()
        {   
            
            if($this->error > 0)
            {
                echo $this->error;
            }
            else
            {
                echo '<br/>'.'start upload';
                if(empty($this->newName)==false)
                {
                    $this->videoFileName = $this->newName.$this->type;
                }
                return move_uploaded_file( $this->tmp_name , $this->videoNewPith.$this->videoFileName );
            }
        }

        private function converterVideo()
	{
            if(empty($this->newName)==false)
            {
                $this->videoName = $this->newName;
            }
            
            try {
                
                $cmd="ffmpeg -i ".$this->videoNewPith.$this->videoFileName." -y -f image2 -ss ".$this->videoTimeForFrontCover ." -t 0.001 -s 640x480 ".$this->imagePith.$this->videoName."_front_cover.jpg";
                exec($cmd,$output,$return_var);     
                $exec=$return_var==0?1:0;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }

            return $exec;
	}
        
        public function run()
        {
            if(is_file($this->videoNewPith.$this->videoName.".flv")==false) 
            {
                if($this->uploadVideo())
                { 
                    if($this->converterVideo())
                    {
                        return true;
                    }
                    else 
                    {
                        return FALSE;
                    }
                }
                else 
                {
                    return FALSE;
                }
            }
            else 
            {
                return FALSE;
            }
        }
        public function removeVideo()
        {
            
            
                $removeFile = $this->removeFileName.'.'.$this->type;
            
                   
            if(is_file($this->videoNewPith.$removeFile)) 
            {
              echo   unlink($this->imagePith.$this->removeFileName."_front_cover.jpg" );
               echo  unlink($this->videoNewPith.$removeFile );
            }
        }
        
        public function __destruct() {
            unset($_FILES);
        }
}

?>