<div class="row">
    <div style="display:none" id="chatlogs" class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div>Chatlogs</div>
            </header>
        </section>
    </div>
    <div id="searchresults" style="display:none" class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Search result<br>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-condensed">
                        <tr><td style="width: 5%;"><b>Room-ID</b></td><td><b>Username</b></td><td><b>Email</b></td><td><b>Current IP</b></td></td></tr>
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
                <div>Search for users</div>
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
                    <label class="col-sm-2 col-sm-2 control-label">Room Number</label>
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
                    <button style="width:130px;margin-right:14px" id="search" class="btn btn-success">Search users</button>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Last registered users<br>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-condensed">
                        <tr><td style="width: 5%;"><b>Room-ID</b></td><td><b>Message-From</b></td><td><b>Message-To</b></td><td><b>Message</b></td></td></td><td><b>Timestamp</b></td></td></tr>
                        <?php
                            $Players = CMS::$MySql->query('SELECT room_id,user_from_id,user_to_id,message,timestamp FROM chatlogs_room ORDER BY timestamp DESC LIMIT 100', array('rank' => Users::$Session->Data['rank']));
                            foreach($Players as $Row)
                            {
                                echo '<tr data-id="'.$Row['room_id'].'">
								            <td>'.$Row["room_id"].'</td>
											<td>'.$Row["user_from_id"].'</td>
											<td>'.$Row["user_to_id"].'</td>
											<td>'.$Row["message"].'</td>
											<td>'. date('d-m-Y H:i:s', $Row['timestamp']).'</td>
											</tr>';
                            }
                        ?>
                    </table>
                </div>
            </header>
    </div>
</div>
<script src="/Simple/Assets/manage/js/loaders/chatlogs.js"></script>