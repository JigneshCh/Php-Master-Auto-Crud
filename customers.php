<?php

/**
 * Created by VisualStudioCode.
 * User: JigneshCh 
 * Date: 04/03/2022
 * Time: 11:00 PM
 */

$model = "customers";
$modeli = "Customer";

$listable = [
  "id" => ["label" => "Id"],
  "full_name" => ["label" => "Name"],
  "email" => ["label" => "Email"],
  "phone" => ["label" => "Phone"],
  "apitoken" => ["label" => "API token"],
  "action" => ["label" => "Action"]
];

define('TITLE', "Home");
include 'header.php';
check_logged_in("admin");

$_RESULT = [];
$item_id = 0;
if (isset($_REQUEST['deleteid'])) {
  $item_id = $_REQUEST['deleteid'];

  $sql = "DELETE FROM $model WHERE ID=" . $item_id;

  if ($conn->query($sql) === TRUE) {
    $_RESULT['SUCCESS']['FORM'] = 'Record deleted';
  } else {
    $_RESULT['ERRORS']['FORM'] = $conn->error;
  }
}

$sql = "SELECT * FROM $model ORDER BY id DESC;";
$items = $conn->query($sql);
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?php echo ucfirst($modeli);  ?></h1>
      <div class="section-header-button">
        <a href="<?php echo $model;  ?>-add.php" class="btn btn-primary">Add New</a>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4><?php echo ucfirst($modeli);  ?> listing</h4>
            <u>
              <?php if (isset($_RESULT['SUCCESS']['FORM'])) {
                echo $_RESULT['SUCCESS']['FORM'];
              } ?>
              <?php if (isset($_RESULT['ERRORS']['FORM'])) {
                echo $_RESULT['ERRORS']['FORM'];
              } ?>
            </u>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped" id="table-2">
                <thead>
                  <tr>
                    <?php foreach ($listable as $key => $fill) { ?>
                      <th><?php if (isset($fill['label'])) {
                            echo $fill['label'];
                          } else echo ucfirst($key);  ?></th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
                <?php while($row = $items->fetch_assoc() ) { ?>	  
                  <tr>
                  <?php foreach($listable as $key=>$fill){ ?>	
                  <td>
                  <?php if($key != "action"){ ?>
                  <?php if(isset($row[$key])){ echo $row[$key]; }else echo "-";  ?>
                  <?php }else{ ?>
                    <div class="action-div">
                        <a href="<?php echo $model;  ?>-edit.php?id=<?php echo $row['id'];?>" class="btn btn-primary btn-action btn-edit mr-1" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                        <a href="<?php echo $model;  ?>.php?deleteid=<?php echo $row['id'];?>" class="btn btn-danger btn-action btn-delete" onclick="return confirm('Are You Sure?|This action can not be undo. Do you want to continue?')" title="Delete"  ><i class="fas fa-trash"></i></a>
                    </div>
                  <?php } ?>
                  </td>
                  <?php } ?>
                  </tr>
                  <?php	}  ?>          
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php include 'footer.php' ?>