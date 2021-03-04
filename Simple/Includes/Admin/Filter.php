<div class="row">
    <div id="filterfield" class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div>Add player to filter</div>
            </header>
            <div class="panel-body">
                <div style="display:none" class="alert alert-block alert-danger"></div>
                <div style="display:none" class="alert alert-block alert-success"></div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input id="filterusername" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">IP address</label>
                    <div class="col-sm-10">
                        <input id="filterip" class="form-control">
                    </div>
                </div><br><br>
                <div id="buttons" style="float:right">
                    <button style="width:130px;margin-right:14px" id="ban" class="btn btn-success">Add player to filter</button>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Added ips<br>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-condensed">
                        <tr><td><b>Ip address</b></td><td><b>Username</b></td><td><b>Added by</b></td><td><b>Remove</b></td></tr>
                        <?php
                        $Bans = CMS::$MySql->query('SELECT cms.id,cms.user_id,cms.ip,u1.username AS username, u2.username AS added_by FROM cms_filter cms LEFT JOIN users u1 ON u1.id = cms.user_id LEFT JOIN users u2 ON u2.id = cms.added_by ORDER BY cms.ip DESC', array('rank' => Users::$Session->Data['rank']));
                        foreach($Bans as $Row)
                        {
                            echo '<tr data-id="'.$Row['id'].'">
                                    <td>'.$Row['ip'].'</td>
                                    <td>'.$Row['username'].'</td>
                                    <td>'.$Row['added_by'].'</td>
									<td><center><div class="delete" data-id="'.$Row['id'].'"><i style="padding-top: 4px; color:red;" class="fa fa-trash"></i></center></div></td>
                                    </tr>';
                        }
                        ?>
                    </table>
                </div>
            </header>
    </div>
</div>
<script src="/Simple/Assets/manage/js/loaders/filter.js?2"></script>
