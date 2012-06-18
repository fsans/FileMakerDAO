FileMakerDAO
============

FileMakerAMF gateway



For each table you should create the remote service extending the FilemakerDao.php class:

<?php
        include_once (DAO_LIB . "filemaker/FilemakerDao.php");

        class FmerrDao extends FilemakerDao {
                public $context         = "xml_fmerr";
                public $valueObject     = "FmerrVO";
        }
?>
The $context refers to the FileMaker LAYOUT name where queries will be targeted. Remember that only the fields placed in this layout can be part of the transaction. It will be a good idea to prefix all web interface specific layouts (we use "xml". You'll find this very usefull when auto generating code (to de docummented as well)

The $valueObject is the name of the class employed as DTO for the referenced table ocurrence. Place VOs in a "vo" folder, the FilemakerDao class uses a class Factory instantiating typed objects on demand, and searches value object class definitions in this folder by default.

Then create the DTO (Value object):

<?php
class FmerrVO {
        
        public function __construct() { }

        public $recid;
        public $sku; 
        public $status; 
        public $type; 
        public $errnum; 
        public $description; 
        public $group; 
        public $coment; 
        public $iid;
        
        public $_explicitType =  "es.ntwk.samples.fmclub.vo.FmerrVO";
        
        public function __set_state ( $assoc ) {
                $this->recid    = (int) $assoc->recid;
                $this->sku      = (string)      $assoc->sku;
                $this->status   = (string)      $assoc->status;
                $this->type     = (string)      $assoc->type;
                $this->errnum   = (string)      $assoc->errnum;
                $this->description      = (string)      $assoc->description;
                $this->group    = (string)      $assoc->group;
                $this->coment   = (string)      $assoc->coment;
                $this->iid      = (string)      $assoc->iid;
        }
}
?>
Note the type conversion that is done on $valueObject fill. You can adjust data types between your app and FileMaker here. For example, if you have a FileMaker Number field use this to fix eventual (enoying) dot/colon separators:

$this->ammount  = (float)       str_replace(",", ".", $assoc->ammount);
For Dates paly with Strings (I'll talk about later)

Create the package at your own, we recommend you to use standards (in this example "es.ntwk.samples.fmclub")

 -> FilemakerDao folder
     -> services
         -> lib (the filemakerdao library)
         -> es
             -> ntwk
                 -> samples
                     -> fmclub
                         FmerrDao (your class)
                         -> vo
                             FmerrVO (your value object)
 
(note: Browse the Source code for the example)

Now you can make calls to the service, like find(), createOne(), updateOne(), deleteOne()...

Use the following params in each call:

$param: one VO filled with relevant values, null for a "findall" or especify recID (recordID) to edit records. $filter: array with the name of the fields to use from the value object, null for all fields $skip: array with the skip & max values, if null returns all records found

The signatures for each function are (as specified in the IFilemakerDao.php interface):

    public function find                ($param, $filter, $skip);
    public function createOne           ($param, $filter                );
    public function updateOne           ($param, $filter                );
    public function deleteOne           ($param                         );
    public function duplicateOne        ($param                         );
    public function view                ($param                         );
    
    // $param is always a VO or null
Note that you can specify globally ignored fields in the config.ini, that you should use in case you have read only fields in $context (such as calculations or autoenter values)