 <style type="text/css">
  img
  {
    margin-top: -55px;
  }
  .validation
  {
    
  }
  p
  {
    margin-left: 50px;
    margin-top: -10px;
    font-style: century gothic;
    font-size: 15px;
    color:red;
  } 
  footer
  {
    margin-top:-40px;
  }


 </style>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>NHFC TIMEKEEPING</title>
      <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/style.css">  
</head>

<body>
  <hgroup>  
 <a href="<?php echo base_url(); ?>login/index"><img src="<?php echo base_url(); ?>/assets/img/Logowithtext.png" width="370px;"></a>
 <a href="<?php echo base_url(); ?>login/index"><img src="<?php echo base_url(); ?>/assets/img/gtlicc.png" width="350px;"></a>
 <h1>Time Keeping Login Form</h1>
</hgroup>
 

<?php echo form_open('user/index'); ?>
  <div class="validation">
     <?php if($this->session->flashdata('login_fail')) : ?>
        <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('login_fail'); ?></p>

     <?php endif; ?>

   
        <?php if($this->session->flashdata('login_failed')) : ?>
        <p class="alert alert-dismissable alert-success"><?php echo $this->session->flashdata('login_failed'); ?></p>

        <?php endif; ?>
        
  </div>
   
  <div class="group">
    <input type="text" class="form-control" name="username" placeholder="Username" />
    <?php echo validation_errors(); ?>
  </div>

  <div class="group">
   <input type="password" class="form-control" name="password" placeholder="Password"  />
  </div>

  <button type="submit" class="button buttonBlue">Login
    <div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
  </button>

<?php echo form_close(); ?>

<footer>

  <p>Copyright NewHorizonFinanceCorp <a href="#">NHFC TIMEKEEPING</a> As 2019-2020. All rights reserved.</p>              
</footer>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="<?php echo base_url(); ?>/assets/js/index.js"></script>

</body>
</html>
