<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div>Create a News Article</div>
                <div style="display:none">Update a News Article</div>
            </header>
            <div class="panel-body">
                <div style="display:none" class="alert alert-block alert-danger"></div>
                <div style="display:none" class="alert alert-block alert-success"></div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        <input id="title" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Short Story</label>
                    <div class="col-sm-10">
                        <input id="shortstory" class="form-control">
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Allow reactions?</label>
                    <div id="reactions" class="col-sm-10">
                        <label class="radio-inline"><input type="radio" name="reactions" value="1" checked="checked" />Yes</label>
                        <label class="radio-inline"><input type="radio" name="reactions" value="0">No</label>
                    </div>
                </div><br><br>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Image</label>
                    <div class="col-sm-10">
                        <select class="form-control" style="width:100%;font-size:14px"
                        <?php
                            foreach(glob('Simple/Assets/manage/img/newsimages/*.{jpg,png,gif}', GLOB_BRACE) as $image) {
                                echo '<option name="topstory">'.basename($image).'</option>';
                            }
                        ?>
                        </select>
                        <br>
                        <style>
                            .imagebox {
                                width: auto;
                                background-repeat: repeat-y;
                                border-radius: 6px;
                                float: left;
                                margin-right: 0.72pc;
                                margin-bottom: 10px;
                                webkit-box-shadow: 0 3px rgba(0,0,0,.17),inset 0px 0px 0px 1px rgba(0,0,0,0.31),inset 0 0 0 2px rgba(255,255,255,0.44)!important;
                                -moz-box-shadow: 0 3px rgba(0,0,0,.17),inset 0px 0px 0px 1px rgba(0,0,0,0.31),inset 0 0 0 2px rgba(255,255,255,0.44)!important;
                                box-shadow: 0 3px rgba(0,0,0,.17),inset 0px 0px 0px 1px rgba(0,0,0,0.31),inset 0 0 0 2px rgba(255,255,255,0.44)!important;
                            }
                        </style>
                        <div class="imagebox">
                            <img id="image" style="border-radius:6px" data-image="choose.gif" src="/Simple/Assets/manage/img/newsimages/choose.gif" border="0">
                        </div>
                        <br><br>
                    </div>
                </div>
                <br><br>
                <script src="/Simple/Assets/manage/js/ckeditor/ckeditor.js"></script>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Long Story</label>
                    <div class="col-sm-10">
                        <textarea id="longstory" rows="15" cols="80"></textarea>
                    </div>
                </div>
                <div id="buttons" style="float:right;margin-top:10px">
                    <button style="width:130px;margin-right:14px" id="place" class="btn btn-success">Post article</button>
                    <button style="width:130px;margin-right:14px;display:none" id="update" data-id="0" class="btn btn-success">Update article</button>
                    <button style="width:130px;margin-right:14px;display:none" id="cancel" class="btn btn-danger">Cancel</button>
                </div>
            </div>
        </section>
    </div>
    </header>
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Most recent articles<br>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-condensed">
                        <tr><td style="width: 5%;"><b>ID</b></td><td><b>Title</b></td><td><b>Shortstory</b></td><td><b>Author</b></td><td><b>Publish date</b></td><td><b><center>Edit</center></b></td></td><td><b><center>Delete</center></b></td></tr>
                        <?php
                        $News = CMS::$MySql->query('SELECT cms_news.id,title,shortstory,published,username FROM cms_news LEFT JOIN users ON users.id = author ORDER by id DESC LIMIT 25');
                            foreach($News as $Row)
                            {
                                echo '<tr data-id="'.$Row['id'].'">
											<td>'.$Row['id'].'</td>
											<td>'.$Row["title"].'</td>
											<td>'.$Row["shortstory"].'</td>
											<td>'.$Row["username"].'</td>
											<td>'. date('d-m-Y', $Row['published']).'</td>
											<td><center><div class="edit" data-id="'.$Row['id'].'"><i style="padding-top: 5px; color:green;" class="fa fa-edit"></i></center></div></td>
											<td><center><div class="delete" data-id="'.$Row['id'].'"><i style="padding-top: 4px; color:red;" class="fa fa-trash"></i></center></div></td>
											</tr>';
                            }
                        ?>
                    </table>
                </div>
                <?php
                $Count = ceil(CMS::$MySql->single('SELECT COUNT(*) FROM cms_news') / 25);
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
<script src="/Simple/Assets/manage/js/loaders/news.js"></script>