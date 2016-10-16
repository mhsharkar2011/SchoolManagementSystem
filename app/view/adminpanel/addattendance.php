<?php 
require_once("../../Config.php");
require_once (SITE_PATH."/controller/PostController.php");
$PostCon=new PostController;
$user_type=$PostCon->user_type();
if (isset($_POST) AND isset($_POST['attendance']))
{
  $results=$PostCon->c_attendance_reports($_POST);
  echo $results;
}
if ($user_type==1 OR $user_type==2) 
{
  if (isset($_GET) AND isset($_GET['sort_class']))
  {
    $result=$PostCon->c_reg_view($_GET['sort_class']);
  }
  else
    $result=$PostCon->c_reg_view();
}
?>
<section id="view_data" class="panel">
  <header class="panel-heading">
    Publish New Result
  </header>
  <div class="panel-body">
    <form method="POST" class="form-horizontal" role="form" id="formId" onSubmit="return formSubmit('formId');">
      <div class="form-group">
        <label for="input" class="col-sm-2 control-label">Select Class</label>
        <div class="col-sm-2">
          <select onchange="select_class(this.value)" class="form-control" name="class_name" id="select_form" onchange="myusertype(this.value)">
            <option value="" selected>Select One</option>
            <?php  
            if (isset($_GET) AND isset($_GET['sort_subject']))
              { $class=$PostCon->c_class_view($_GET['sort_subject']);}
            else
              { $class=$PostCon->c_class_view(); }
            foreach ($class as $cvalue) { ?>
            <option value="<?php echo $cvalue['class_id'] ?>" <?php if ( isset($_GET) AND isset($_GET['sort_class']) AND $_GET['sort_class']==$cvalue['class_id']) { echo "SELECTED"; }  ?> > <?php echo $cvalue['c_name']; ?></option>
            <?php } ?>
          </select>
        </div>
        <label for="input" class="col-sm-2 control-label">Select Subject</label>
        <div class="col-sm-2">
          <select  class="form-control" name="subject_name" id="select_form" onchange="myusertype(this.value)">
            <option value="" selected>Select One</option>
            <?php  
            if (isset($_GET) AND isset($_GET['sort_class']))
              {  $subject=$PostCon->c_subject_view($_GET['sort_class']);}
            else
              { $subject=$PostCon->c_subject_view();}
            foreach ($subject as $svalue) { ?>
            <option value="<?php echo $svalue['subject_id'] ?>  "> <?php echo $svalue['s_name']; ?></option>
            <?php } ?>
          </select>
        </div>
        <label for="input" class="col-sm-1 control-label">Date</label>
        <div class="col-sm-2">
          <input type="datetime" name="date" disabled value="<?php echo date('d-M-Y') ?>">
        </div>
      </div>
      <div class="form-group">
      </div>
      <table  class="table table-striped table-advance table-hover">
        <tbody>
          <tr>
            <th><i class="icon_profile"></i>Student Name</th>
            <th><i class="fa fa-user-md"></i>ID Number</th>
            <th><i class="fa fa-user-md"></i>Present/Absent</th>
            <th><i class="fa fa-user-md"></i>Remarks</th>
          </tr>
          <?php
          foreach ($result as $value) { ?>
          <tr>
            <td><?php echo $value['user_f_name']; ?></td>
            <td><?php echo $value['s_reg_num']; ?></td>
            <input type="hidden" name="reg_number[]" value="<?php echo $value['s_reg_num']; ?>" >
            <input type="hidden" name="published_by[]" value="<?php echo $_SESSION['user_id'] ?>" >
            <td>
              <div class="form-group">
                <div class="col-lg-4">
                  <input type="checkbox" id="checkboxstatus" value="1" name='attendance[]'>
                </div>
              </div>
            </td>
            <td>
              <div class="form-group">
                <div class="col-lg-10">
                  <input type="textarea" name='r_remarks[]'class="form-control" id="inputtextarea">
                </div>
              </div>
            </td>
          </tr>
          <?php } ?>             
        </tbody>
      </table>
      <div class="col-lg-offset-2 col-lg-5">
        <button type="submit" class="btn btn-success" value="submit_result">Submit</button>
      </div>
    </form>
  </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
  function select_class(str) {
    var xhttp; 
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == 4 && xhttp.status == 200) {
        document.getElementById("view_data").innerHTML = xhttp.responseText;
      }
    }
    xhttp.open("GET", "addattendance.php?sort_class="+str, true);
    xhttp.send();
  }
//Check the checkbox ix checked or unchecked if checked return true else return 0
function formSubmit(formId){
var theForm = document.getElementById(formId); // get the form
var attendance = theForm.getElementsByTagName('input'); // get the inputs
for(var i=0;i<attendance.length;i++){ 
if(attendance[i].type=='checkbox' && !attendance[i].checked)  // if this is an unchecked checkbox
{
attendance[i].value = 0; // set the value to "off"
attendance[i].checked = true; // make sure it submits
}
}
return true;
}
</script>