<?php foreach ($recommendToFollow as $key => $value): ?>
    <?php $memberEntity = $value; ?>
    <tr>
        <td class="td-vendor-img">
           <a href="/<?=html_escape($memberEntity->getSlug());?>"><img src="<?php echo getAssetsDomain().'.'.$value->avatarImage?>" class="vendor-img"/></a>
        </td>
        <td class="td-vendor-details">
            <a href="/<?=html_escape($memberEntity->getSlug());?>">
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
            <span class="follow-btn follow-right btn btn-default-2 subscription" data-status="follow" data-slug="<?=html_escape($memberEntity->getSlug()); ?>" data-btn="recommend" data-username="<?=html_escape($memberEntity->getUsername());?>">
                <span class="glyphicon glyphicon-plus-sign"></span>Follow
            </span>
        </td>
    </tr>
<?php endforeach; ?>
