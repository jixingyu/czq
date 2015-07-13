<?php include('_header.php');?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">
            关于我们
        </h1>

        <div class="text-num">
            <form role="form" action="/admin/app_config/about" method="post">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        关于我们设置
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>介绍</label>
                            <textarea class="form-control" name="introduction" rows="10"><?php if (!empty($about['introduction'])) echo $about['introduction'];?></textarea>
                        </div>
                        <div class="form-group">
                            <label>客服电话</label>
                            <input class="form-control" type="text" name="phone" value="<?php if (!empty($about['phone'])) echo $about['phone'];?>">
                        </div>
                        <div class="form-group">
                            <label>客服QQ</label>
                            <input class="form-control" type="text" name="qq" value="<?php if (!empty($about['qq'])) echo $about['qq'];?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">提交</button>
            </form>
        </div>
    </div>
</div>

<?php include('_footer.php');?>