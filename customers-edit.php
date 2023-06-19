<?php

/**
 * Created by VisualStudioCode.
 * User: JigneshCh 
 * Date: 04/03/2022
 * Time: 10:15 PM
 */

$model = "customers";
$modeli = "Customer";

$fillable = [
	"full_name" => ["label" => "Name", "type" => "text", "require" => "1"],
	"email" => ["label" => "Email", "type" => "text", "require" => "1", "email" => "1", "unique" => "1"],
	"password" => ["label" => "Password", "type" => "password", "require" => "1", "hash" => 0],
	"phone" => ["label" => "Phone", "type" => "text", "require" => "1"],
	"address" => ["label" => "Address", "type" => "textarea", "require" => "1"],
	"apitoken" => ["label" => "API token", "type" => "text", "require" => "1"],
	"api_url" => ["label" => "API url", "type" => "text", "require" => "1"],
	"api_method" => ["label" => "API method", "type" => "select", "require" => "1", "option" => ["post", "get", "put", "delete"]],
	//"api_params"=>["label"=>"Parameters","type"=>"text_json","require"=>"0"],
	"status" => ["label" => "Status", "type" => "select", "require" => "1", "option" => ["1", "0"]]
];

define('TITLE', "Edit " . $modeli);
include 'header.php';
check_logged_in("admin");

$_RESULT = [];
$item_id = 0;
if (isset($_REQUEST['id'])) {
	$item_id = $_REQUEST['id'];
}

if ($item_id) {

	$sql = "SELECT * FROM $model where id=" . $item_id . " LIMIT 1";  // Select ONLY one, instead of all
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	if (isset($row) && isset($row['id'])) {
		$item = $row;
	} else {
		$_SESSION['STATUS']['editstatus'] = 'Request could not be validated';
		header("Location: $model.php");
		exit();
	}
} else {
	$_SESSION['STATUS']['editstatus'] = 'Request could not be validated';
	header("Location: $model.php");
	exit();
}

if (isset($_POST[$model . '-edit'])) {

	$itempost = $_POST;
	$Profilepic = null;
	foreach ($fillable as $key => $fill) {
		if (isset($fill['require']) && $fill['require'] == 1 && isset($itempost[$key]) && $itempost[$key] == "") {
			$_RESULT['ERRORS'][$key] = ucfirst($fill['label']) . ' is require';
		} else if (isset($fill['email']) && $fill['email'] == 1 && isset($itempost[$key]) && !filter_var($itempost[$key], FILTER_VALIDATE_EMAIL)) {
			$_RESULT['ERRORS'][$key] = 'invalid email, try again';
		} else if (isset($fill['unique']) && $fill['unique'] == 1 && isset($itempost[$key]) && !availableEmail($conn, $itempost[$key], $model)) {
			if ($item[$key] != $itempost[$key]) {
				$_RESULT['ERRORS'][$key] = 'email already taken';
			}
		}
	}
	if (!isset($_RESULT['ERRORS'])) {
		$inputs_key = [];
		$inputs_vla = [];
		$valueSets = array();
		foreach ($itempost as $k => $value) {
			if (isset($fillable[$k])) {
				$_value = $value;
				if (isset($fillable[$k]['hash']) && $fillable[$k]['hash'] == 1) {
					$_value = password_hash($value, PASSWORD_DEFAULT);
				} else if (isset($fillable[$k]['type']) && $fillable[$k]['type'] == "text_json" && is_array($value)) {
					$_value = json_encode($value);
				}
				$inputs_key[] = $k;
				$inputs_vla[] = "'" . $_value . "'";
				$valueSets[] = $k . " = '" . $_value . "'";
			}
		}
		$columns = implode(", ", $inputs_key);
		$values  = implode(", ", $inputs_vla);
		$sql = "UPDATE `$model` SET " . join(",", $valueSets) . " WHERE `id` = $item_id;";
		if ($conn->query($sql) === TRUE) {
			$_RESULT['SUCCESS']['FORM'] = $modeli . ' updated';
			unset($itempost);
		} else {
			$_RESULT['ERRORS']['FORM'] = $conn->error;
		}
	}
}

if ($item_id) {
	$sql = "SELECT * FROM $model where id=" . $item_id . " LIMIT 1";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	if (isset($row) && isset($row['id'])) {
		$item = $row;
	} else {
		$_SESSION['STATUS']['editstatus'] = 'Request could not be validated';
		header("Location: $model.php");
		exit();
	}
}
?>
<!-- Main Content -->
<div class="main-content">
	<section class="section">

		<div class="section-header">
			<div class="section-header-back">
				<a href="<?php echo $model;  ?>.php" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
			</div>
			<h1>Edit <?php echo $modeli;  ?> </h1>
			<div class="section-header-breadcrumb">
				<div class="breadcrumb-item active"><a href="<?php echo $model;  ?>.php"><?php echo $modeli;  ?></a></div>
			</div>
		</div>

		<div class="section-body">

			<div class="row mt-sm-4">
				<div class="col-12 col-md-12 col-lg-6">
					<div class="card">
						<form method="post" class="needs-validation" novalidate="" action="<?php echo $model;  ?>-edit.php" method="post" enctype="multipart/form-data" autocomplete="off">
							<input type="hidden" name="id" value="<?php echo $item_id; ?>" />
							<div class="card-header">
								<h4>Edit <?php echo $modeli;  ?></h4>
								<?php if (isset($_RESULT['SUCCESS']['FORM'])) {
									echo $_RESULT['SUCCESS']['FORM'];
								} ?>
								<?php if (isset($_RESULT['ERRORS']['FORM'])) {
									echo $_RESULT['ERRORS']['FORM'];
								} ?>
							</div>
							<div class="card-body">
								<div class="row">
									<?php foreach($fillable as $key=>$fill){ ?>
									<div class="form-group col-md-12 col-12">
										<label><?php if(isset($fill['label'])){ echo $fill['label']; }else echo ucfirst($key);  ?></label>
										<?php if(isset($fill['type']) && $fill['type'] =="text"){ ?>
											<input value="<?php if(isset($item) && isset($item[$key])){ echo $item[$key]; } ?>" type="text" name="<?php echo $key; ?>" class="form-control">
										<?php } else if(isset($fill['type']) && $fill['type'] =="password"){ ?>
											<input value="<?php if(isset($item) && isset($item[$key])){ echo $item[$key]; } ?>" type="password" name="<?php echo $key; ?>" class="form-control">	
										<?php } else if(isset($fill['type']) && $fill['type'] =="textarea"){ ?>
											<textarea name="<?php echo $key; ?>" cols="30" rows="3" class="form-control"><?php if(isset($item) && isset($item[$key])){ echo $item[$key]; } ?></textarea>
										<?php } else if(isset($fill['type']) && $fill['type'] =="select"){ ?>
											<select name="<?php echo $key; ?>" class="form-control">
												<?php foreach($fill['option'] as $j=>$opv){ ?>
												<option  value="<?php echo $opv; ?>" <?php if(isset($item) && isset($item[$key]) && $item[$key] == $opv ){ echo "selected"; } ?> > <?php echo $opv; ?> </option>
												<?php }  ?>		
											</select>	
										<?php } if (isset($_RESULT['ERRORS'][$key])){ echo $_RESULT['ERRORS'][$key]; } ?>		
									</div>	
									<?php } ?>
								</div>
							</div>
							<div class="card-footer text-right">
								<button type="submit" name='<?php echo $model;  ?>-edit' class="btn btn-primary">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php include 'footer.php' ?>