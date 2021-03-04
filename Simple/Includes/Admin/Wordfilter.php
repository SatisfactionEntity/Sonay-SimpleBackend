<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div>Add a word</div>
            </header>
            <div class="panel-body">
                <div style="display:none" class="alert alert-block alert-danger"></div>
                <div style="display:none" class="alert alert-block alert-success"></div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Word</label>
                    <div class="col-sm-10">
                        <input id="word" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Replacement</label>
                    <div class="col-sm-10">
                        <input id="replacement" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Hide whole message if typed?</label>
                    <div id="hide" class="col-sm-10">
                        <label class="radio-inline"><input type="radio" name="hide" value="1" checked="checked" />Yes</label>
                        <label class="radio-inline"><input type="radio" name="hide" value="0">No</label>
                    </div>
                </div><br><br>
                <div id="buttons" style="float:right">
                    <button style="width:130px;margin-right:14px" id="add" class="btn btn-success">Add to wordfilter</button>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Last added words<br>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-condensed">
                        <tr><td><b>ID</b></td><td><b>Word</b></td><td><b>Replacement</b></td><td><b>Hide</b></td><td><b>Added by</b></td><td><b><center>Delete</center></b></td></tr>
                        <?php
                        $Words = CMS::$MySql->query('SELECT wordfilter.id,`key`,replacement,hide,username FROM wordfilter LEFT JOIN users ON users.id = added_by ORDER by wordfilter.id DESC LIMIT 25');
                        foreach($Words as $Row)
                        {
                            echo '<tr data-id="'.$Row['id'].'">
                                    <td>'.$Row['id'].'</td>
                                    <td>'.htmlspecialchars($Row['key']).'</td>
                                    <td>'.htmlspecialchars($Row['replacement']).'</td>
                                    <td>'.$Row["hide"].'</td>
                                    <td>'.$Row["username"].'</td>
                                    <td><center><div class="delete" data-id="'.$Row['id'].'"><i style="padding-top: 4px; color:red;" class="fa fa-trash"></i></center></div></td>
                                    </tr>';
                        }
                        ?>
                    </table>
                </div>
                <?php
                $Count = ceil(CMS::$MySql->single('SELECT COUNT(*) FROM wordfilter') / 25);
                if ($Count >= 2) {
                    echo '<ul class="pagination"><li class="page-item disabled"><span data-id="-" class="page-link">Previous</span></li>';
                    for ($i = 1; $i <= $Count; $i++) {
                        echo '<li class="page-item ' . ($i == 1 ? 'active' : '') . '" data-id="' . $i . '"><span class="page-link" data-id="' . $i . '">' . $i . '</span></li>';
                    }
                    echo '<li class="page-item ' . ($i <= 2 ? 'disabled' : '') . '"><span class="page-link" data-id="+">Next</span></li></ul>';
                }
                ?>
            </header>
    </div>
</div>
<script src="/Simple/Assets/manage/js/loaders/wordfilter.js"></script>