<?defined('_JEXEC') or die('Restricted access'); ?>
<p class='errMsg'><?=$this->err ?></p>
<? if($this->editor): ?>
<h1>Upload new banner</h1>
<form id="uploadNewBanner" enctype="multipart/form-data" action="<?=$this->action?>" method="post">
    Picture: <input type="file" name="file" /><br />
    Link: <input type="text" name="link" size="34"/><br />
    <input type="submit" value="Send" />
</form>
<? endif; ?>
<h1>Banners</h1>
<? if(count($this->banners) >= 1): ?>
<? foreach($this->banners as $banner): ?>
<div class="frontBanner">
    <a href="<?=$banner["link"]?>" target="_blank">
        <img src="<?=$banner["path"]?>">
    </a>
    <? if($this->editor): ?>
    <br />
    <span class="delBanner"><a href="<?=JRoute::_("index.php?option=com_frontbanner&task=delete&id=".$banner['id'])?>">Delete</a></span>
    <? endif; ?>
</div>
<? endforeach;?>
<? endif; ?>
