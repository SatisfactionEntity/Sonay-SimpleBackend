<link rel="stylesheet" href="/Simple/Assets/css/values/style.css">
<script>
    var SimpleCMS = {
        furni: '<?= CMS::$Config['cms']['furniiconmap'] ?>'
    }
</script>
<div class="row">
    <div class="col-md-4">
        <section class="panel">
            <header class="panel-heading">
                <div>Value pages</div>
            </header>
            <div class="panel-body">
                <div class="list-group">
                    <?php
                    $Values = CMS::$MySql->query('SELECT cms_values_categories.id,name,count(cms_values.id) AS count FROM cms_values_categories LEFT JOIN cms_values ON cms_values.category_id = cms_values_categories.id GROUP BY cms_values_categories.id ORDER BY cms_values_categories.order_num ASC');
                    foreach ($Values as $Row) {
                        echo '<a href="#" data-id="'.$Row['id'].'" class="list-group-item"><span class="name">'.$Row['name'].'</span><span class="badge badge-default badge-pill">'.$Row['count'].'</span></a>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-8">
        <section class="panel">
            <header class="panel-heading">
                Add value page<br>
            </header>
            <div class="panel-body">
                <div style="display:none" class="alert alert-block alert-danger"></div>
                <div style="display:none" class="alert alert-block alert-success"></div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                        <input maxlength="25" class="form-control" required>
                    </div>
                </div><br><br>
                <div style="float:right;margin-top:10px">
                    <button style="width:130px;margin-right:14px" class="btn btn-success">Add page</button>
                </div>
            </div>
    </div>
    <div style="display:none;float:right" class="col-md-8">
        <section class="panel">
            <header class="panel-heading">Add item to current page</header>
            <div class="panel-body">
                <div style="display:none" class="alert alert-block alert-danger"></div>
                <div style="display:none" class="alert alert-block alert-success"></div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Display name</label>
                    <div class="col-sm-10">
                        <input maxlength="25" class="form-control" required>
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Item name</label>
                    <div class="col-sm-10">
                        <input maxlength="50" class="form-control" required>
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Price in SS</label>
                    <div class="col-sm-10">
                        <input type="number" min="0" class="form-control" required>
                    </div>
                </div><br><br>
                <div style="float:right;margin-top:10px">
                    <button style="width:130px;margin-right:14px" class="btn btn-success">Add item</button>
                    <button style="width:130px;margin-right:14px" class="btn btn-danger">Close</button>
                </div>
            </div>
    </div>
    <div style="display:none;float:right" data-id="0" class="col-md-8">
        <section class="panel">
            <header contenteditable="true" class="panel-heading"></header>
            <div class="panel-body">
                <div class="items">
                </div>
                <div style="clear:both"></div>
                <div style="float:right;margin-top:10px">
                    <button style="width:130px;margin-right:14px" class="btn btn-success">Add item to page</button>
                    <button style="width:130px;margin-right:14px" class="btn btn-danger">Delete page</button>
                </div>
            </div>
    </div>
</div>
<script src="/Simple/Assets/manage/js/loaders/values.js"></script>
