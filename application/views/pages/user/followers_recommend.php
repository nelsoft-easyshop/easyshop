<?php foreach ($recommendToFollow as $key => $value): ?>
    <?php $memberEntity = $value; ?>
    <tr>
        <td class="td-vendor-img">
           <a href="/<?=$memberEntity->getSlug();?>"><img src="<?=$value->avatarImage?>" class="vendor-img"/></a>
        </td>
        <td class="td-vendor-details">
            <a href="/<?=$memberEntity->getSlug();?>">
                <p class="p-vendor-name">
                    <?=strlen($memberEntity->getStoreName()) > 0 ? html_escape($memberEntity->getStoreName()) : html_escape($memberEntity->getUsername()); ?>
                </p>
            </a>
            <p class="p-vendor-location">
                <?php if($value->location):?>
                    <?=$value->city;?>, <?=$value->stateRegion;?>
                <?php else: ?>
                    Location not set
                <?php endif; ?>
            </p>
            <span class="follow-btn follow-right btn btn-default-2 subscription" data-status="follow" data-slug="<?=$memberEntity->getSlug(); ?>" data-btn="recommend" data-username="<?=$memberEntity->getUsername();?>">
                <span class="glyphicon glyphicon-plus-sign"></span>Follow
            </span>
        </td>
    </tr>
<?php endforeach; ?>