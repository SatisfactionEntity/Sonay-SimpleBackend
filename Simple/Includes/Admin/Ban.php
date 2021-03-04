<div class="row">
    <div id="banfield" class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div>Ban a user</div>
            </header>
            <div class="panel-body">
                <div style="display:none" class="alert alert-block alert-danger"></div>
                <div style="display:none" class="alert alert-block alert-success"></div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input id="banusername" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Type ban</label>
                    <div class="col-sm-10">
                        <select id="type" class="form-control" style="width:100%;font-size:14px">
                            <option data-id="1">Account</option>
                            <option data-id="2">IP</option>
                        </select>
                    </div><br><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Ban time format</label>
                    <div class="col-sm-10">
                        <select id="format" class="form-control" style="width:100%;font-size:14px">
                            <option data-time="60">Minutes</option>
                            <option data-time="3600">Hours</option>
                            <option data-time="2592000">Months</option>
                            <option data-time="31104000">Years</option>
                        </select>
                    </div><br><br><br>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">Ban time</label>
                        <div class="col-sm-10">
                            <input type="number" min="1" id="time" value="10" class="form-control">
                        </div>
                    </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Reason</label>
                    <div class="col-sm-10">
                        <input id="reason" class="form-control">
                    </div>
                </div><br><br>
                <div id="buttons" style="float:right">
                    <button style="width:130px;margin-right:14px" id="ban" class="btn btn-success">Ban player</button>
                </div>
            </div>
        </section>
    </div>
    <div id="searchresults" style="display:none" class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Search result<br>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-condensed">
                        <tr><td><b>ID</b></td><td><b>Username</b></td><td><b>Type</b></td><td><b>Banned by</b></td><td><b>Expire</b></td><td><b>Reden</b></td><td><b><center>Delete</center></b></td></tr>
                    </table>
                    <div id="buttons" style="float:right;margin-top:10px">
                        <button style="width:130px;margin-right:14px" id="close" class="btn btn-danger">Close</button>
                    </div>
                </div>
            </header>
    </div>
    <div id="searchfield" class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div>Search for bans</div>
            </header>
            <div class="panel-body">
                <div style="display:none" class="alert alert-block alert-danger"></div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input id="username" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">IP address</label>
                    <div class="col-sm-10">
                        <input id="ip" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Exact search?</label>
                    <div id="strict" class="col-sm-10">
                        <label class="radio-inline"><input type="radio" name="strict" value="1">Yes</label>
                        <label class="radio-inline"><input type="radio" name="strict" value="0" checked="checked" />No</label>
                    </div>
                </div><br><br>
                <div id="buttons" style="float:right;margin-top:10px">
                    <button style="width:130px;margin-right:14px" id="search" class="btn btn-success">Search bans</button>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Last active bans<br>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-condensed">
                        <tr><td><b>ID</b></td><td><b>Username</b></td><td><b>Type</b></td><td><b>Banned by</b></td><td><b>Expire</b></td><td><b>Reden</b></td><td><b><center>Delete</center></b></td></tr>
                        <?php
                        $Bans = CMS::$MySql->query('SELECT bans.id,bans.user_id AS userid,u1.username,u2.username AS staff_username,ban_expire,type,ban_reason FROM bans LEFT JOIN users u1 ON u1.id = bans.user_id LEFT JOIN users u2 ON u2.id = bans.user_staff_id WHERE ban_expire > UNIX_TIMESTAMP() AND u1.rank < :rank ORDER BY bans.id DESC LIMIT 25', array('rank' => Users::$Session->Data['rank']));
                        foreach($Bans as $Row)
                        {
                            echo '<tr data-id="'.$Row['id'].'">
                                    <td>'.$Row['id'].'</td>
                                    <td>'.$Row['username'].'</td>
                                    <td>'.$Row['type'].'</td>
                                    <td>'.$Row['staff_username'].'</td>
                                    <td>'. date('d-m-Y H:i:s', $Row['ban_expire']).'</td>
                                    <td>'.($Row['ban_reason'] ? htmlspecialchars($Row['ban_reason']) : CMS::$Lang['defaultbanreason']).'</td>
                                    <td><center><div class="delete" data-id="'.$Row['id'].'"><i style="padding-top:4px;color:red" class="fa fa-trash"></i></center></div></td>
                                    </tr>';
                        }
                        ?>
                    </table>
                </div>
                <?php
                $Count = ceil(CMS::$MySql->single('SELECT COUNT(*) FROM bans LEFT JOIN users ON users.id = bans.user_id WHERE ban_expire > UNIX_TIMESTAMP() AND rank < :rank', array('rank' => Users::$Session->Data['rank'])) / 25);
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
<script src="/Simple/Assets/manage/js/loaders/ban.js"></script>
