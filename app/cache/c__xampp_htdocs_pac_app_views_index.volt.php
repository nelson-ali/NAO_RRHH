<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <?php echo $this->tag->getTitle(); ?>
        <?php echo $this->tag->stylesheetLink('css/bootstrap/bootstrap.min.css'); ?>
        <?php echo $this->tag->stylesheetLink('css/bootstrap/bootstrap-responsive.min.css'); ?>
        <?php echo $this->tag->stylesheetLink('css/style.css'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Your invoices">
        <meta name="author" content="Phalcon Team">
    </head>
    <body>
        <?php echo $this->getContent(); ?>
        <?php echo $this->tag->javascriptInclude('js/jquery/jquery.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/bootstrap/bootstrap.min.js'); ?>        
    </body>
</html>