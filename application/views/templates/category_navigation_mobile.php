
<div class="panel-group " id="categories">
    <div class="panel panel-default panel-category no-border border-0">
        <div class="panel-heading panel-category-heading no-border">
            <h4 class="panel-title panel-title-category">
                Categories
                <a data-toggle="collapse" data-parent="#categories" href="#categories-body">
                    <img class="pull-right" src="/assets/images/img_arrow_down.png">
                </a>
            </h4>
        </div>
        <div id="categories-body" class="panel-collapse collapse">
            <div class="panel-body-category">
                <ul class="list-unstyled">
                    <?php foreach ($parentCategory as $key => $value): ?>
                    <a href="/category/<?=$value->getSlug(); ?>"><li class="list-category"><?=$value->getName();?></li></a>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>