<?php


//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);


//instantiate the program object

//Class to load classes it finds the file when the progrm starts to fail for calling a missing class


class Manage
{

    public static function autoload($class)
    {  
        //you can put any file name or directory here
	  include $class . '.php';
	   
     }
     
   }
       
        spl_autoload_register(array('Manage', 'autoload'));
        //instantiate the program object
        $obj = new main();


class main
{
    public function __construct()
        {
	   
	   //set default page request when no parameters are in URL
	        $pageRequest = 'uploadform';
	   
	   //check if there are parameters

               if(isset($_REQUEST['page']))
	       {
	         //load the type of page the request wants into page request
		     $pageRequest = $_REQUEST['page'];
	        }
	
	  //instantiate the class that is being requested
	        $page = new $pageRequest;
	
	        if($_SERVER['REQUEST_METHOD'] == 'GET')
	        {
	             $page->get();
	        }
	        else
	        {   
		     $page->post();
		 }
          
	  }
 }

      
        
        abstract class page
             {
              protected $html;

              public function __construct()
                {
                  $this->html .= '<html>';
	          $this->html .= '<body>';   
                }
             
	     public function __destruct()
                {
                  $this->html .= '</body></html>';
	          echo $this->html;
                }
        
 	     public function get()
	       {
	          echo 'default get message';
	       }
	        
   	     public function post()
 	       {
 	          print_r($_POST);
               }
            }



         ////Making uploadform
	 class makeform
           {
             static  public  function formula()
                { 

		  //Returning $form into HTML 
                  $form = '<form action="index.php?page=uploadform"
                  method="post"
                  enctype="multipart/form-data">';

                  $form .= '<input type="file"  name="fileToUpload"
	          id="fileToUpload">';
         
	          $form .= '<input type="submit" value="Upload File" name="submit">';
	          $form .= '</form> ';
           
	          return $form;

                }
            }


         ///Creating StaticHeader to Redirect the File
	 class staticheader
	   {
	    static public function callheader($target_file)
	         {
		    //Calling Table class & sending $target_file as parameter

		    header('Location: index.php?page=Table&doc=' . $target_file);
		    
		 }
           }

        
	/// Calling the Above Two Classes in uploadform class
         class uploadform extends page
           {
            //Form to Upload CSV File
            public function get()
               {
	
	           $this->html .='<h1>Upload Form</h1>';
	           $this->html .= makeform::formula();

	       }

         public	function post()
 	       {
	    
	           $target_dir="uploads/";
                   $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
           
	           if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
	               {

	                    $this->html .= staticheader::callheader($target_file);
	                    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been submitted.";
		      
		        } 
            
	            else
	               {
	                    echo "Sorry, there was an error uploading your file.";
                        }
	     
	         } 
      
             } 
      


            class  Table extends  page
                 {
                   public function get()
                       {

	                  //Get $target_file from  Header Function 
	                  $filepath=$_GET["doc"];
	                  $actualFile=fopen($filepath,'r') ;

	                  $numbers ='<table border="1">';
             
	                   //Runs the loop till the End Of the File
	                   while(!feof($actualFile))
	                     {
	                       $data = fgetcsv($actualFile);
		                  
				//Removes the Comma 
  		                // $Splitting_values=explode(",",$data);
                    
		                //TypeCasting
		                $array=(array) $data;
		   
		                //Start of the Row
                                $numbers .= '<tr>';
 
                                //Displaying Table Cell Values
                                foreach($array as $values)
	                          {
		                      $numbers .= '<th>'.$values.'</th>';
		                   }
		                   
		               $numbers .= '</tr>';
			     }

		 	  $numbers .= '</table>';
                          $this->html .= $numbers;

			  //Closes the File
			  fclose($actualFile);
                
	                
			}
		  
                    }

        
	   

    
?>
