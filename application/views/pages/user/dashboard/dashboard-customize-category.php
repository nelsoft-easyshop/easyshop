<div class="div-tab">
    <div class="dashboard-breadcrumb">
        <ul>
            <li>Dashboard</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>My Store</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>Store Setup</li>
        </ul>
    </div>
      
    <div id="category-tree-reference" style="display:none;">
        <ul>
        </ul>
    </div>

    <div class="div-tab-inner">
    
        <div class="div-store-setup category-setup-loading" style="text-align:center">
            <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-md.gif"/>
            <br/><br/>
            <p class="loading-text">Loading your category lists...</p>
        </div>
    
        <div class="category-setup-ajax" style="display:none"> 
    
            <div class="div-store-setup">
                <p class="panel-setup-title">CATEGORIES</p>
                <div id="div-store-content-edit" class="div-store-content concealable">
                                 
                    <div class="current-store-cat">
                        <div class="form-horizontal">
                        <div class="form-group">
                                <label class="col-sm-3 control-label">Current Order : </label>
                                <div class="col-sm-9 col-with-radio">

                                    <div class="store-category-view">

                                    </div>
                                    
                                    <div>
                                        <br/>   
                                        <button class="btn btn-setting-edit-btn" id="btn-edit-store-cat">
                                        Reorder/Edit Categories
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="edit-store-cat form-horizontal">
                    <div class="form-group">
                            <label class="col-sm-3 control-label">Reorder List : </label>
                            <div class="col-sm-6 col-with-radio">
                                
                                <div id="edit-category-tree">
                                </div>
                                
                                
                                <p class="note-category">Drag the category items to reorder</p>
                                <br/>
                                <button class="btn btn-setting-save-btn" id="category-order-save">
                                Save Changes
                                </button>
                                <button class="btn btn-setting-cancel-btn" id="cancel-edit-store-cat">
                                Cancel
                                </button>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <p class="p-note-setting">
                        Arrange the order of the categories in your store page based on your preference. The first category will always appear first
                        when someone views your store. You may also edit the products that appear within your categories.
                    </p>
                </div>
      
                <br/>
                
                <div id="no-category-display-edit" class="jumbotron no-items no-category-display" style="display:none">
                    <i class="icon-category"></i> No available category
                </div>
      
            </div>
            

            <div class="div-store-setup">
                <p class="panel-setup-title">NEW CATEGORY</p>
                <div class="div-store-content">
                    <div class="add-store-cat">
                        <div class="alert alert-es-success add-store-cat-message" role="alert" style="display:none;">
                                Product category has been successfully added
                        </div> 
                        <button class="btn btn-setting-edit-btn" id="add-category">
                        ADD NEW CATEGORY
                        </button>
                    </div>
                    <p class="p-note-setting">
                        Add a new category to your list of store categories and assign products to your new category. 
                    </p>
                </div>

            </div>


            <div class="div-store-setup">
                <p class="panel-setup-title">DELETE CATEGORIES</p>
                <div id="div-store-content-delete" class="div-store-content concealable">
                    <div class="delete-store-cat">
                        <div class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal">
                                    <div class="alert alert-es-danger delete-dialog-fail" role="alert" style="display:none">
                                        <span class="glyphicon glyphicon-exclamation-sign"></span>
                                        <span class="message">Sorry, something went wrong. Please try again later.</span>
                                    </div>
                                                    
                                    <div class="alert alert-es-success delete-dialog-success" role="alert" style="display:none">
                                            Product categories successfully deleted.
                                    </div> 
                                </form>
                            </div>
                        </div>
                        <div class="form-group">
                                <label class="col-sm-3 control-label">Current Categories : </label>
                                <div class="col-sm-6 col-with-radio">
                                    <div id="delete-category-tree">
                                    </div>
                                    
                                    <div class="current-btn-delete">
                                        <br/>   
                                        <button class="btn btn-setting-cancel-btn" id="btn-edit-delete-categories">
                                            Delete
                                        </button>
                                    </div>
                                    <br/>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="p-note-setting">
                        Delete any of your current categories by selecting them from the list above. Clean up your store page by removing
                        duplicate categories.
                    </p>
                </div>

                <br/>
                <div id="no-category-display-delete" class="jumbotron no-items no-category-display" style="display:none">
                    <i class="icon-category"></i> No available category
                </div>

            </div>
        
        
        </div>
    
    
    </div>
</div>

<!--For ADD category modal-->
<div class="add-category-modal category-modal">
    <h3 class="my-modal-title">
        Add Category
    </h3>
    <div class="category-form">
        <div class="form-horizontal">
           <div class="form-group">
                <label class="col-sm-3">Category Name : </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control category-name"  placeholder="Type the category name here" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3">Parent Category : </label>
                <div class="col-md-9">
                    <select class="parent-category-dropdown form-control category-name">
                        <option value="0">None</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Add items to this category : </label>
            <div class="customized-category-error">
                <div class="alert alert-es-danger" role="alert">
                    <a href="javascript:void(0)" class="alert-link error-message" style="border:none;"></a>
                </div>
            </div>
        </div>
 
        <div class="drag-drop-container">
            <div class="row">
                <div class="col-xs-6 custom-left-panel">
                    <div class="drag-drop-group-list">
                        <div class="category-panel-header">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="category-panel-title">My Category</span>
                                </div>
                            </div>
                        </div>
                        <div class="category-items-holder my-category-container category-items" >
                            <ul class="customCategory category-product-list product-list">
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 all-right-panel">
                    <div class="drag-drop-group-list">
                        <div class="category-panel-header">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="category-panel-title">All Items</span>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="search-category" placeholder="Search product from list...">
                                    <input type="hidden" class="isSearching" value="false">
                                </div>
                            </div>
                        </div>
                        <div class="category-items-holder my-category-container all-items " data-page="1" data-isComplete="false" data-isProcessing="false">
                            <ul class="allItems ui-helper-reset ui-helper-clearfix all-product-list product-list">
                            </ul>
                            <div class="loader clear" style="text-align:center; display:none; font-size:10px">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-sm.gif" />
                                <br/>
                                Loading more items...
                            </div>                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="my-modal-footer">
        <center>
            <button class="btn btn-default-3 save-new-category">Add Category</button>
            <button class="btn btn-default-1 simplemodal-close">Cancel</button>
        </center>
    </div>
</div>


<!--For EDIT category modal-->
<div class="edit-category-modal category-modal">
    <h3 class="my-modal-title">
        Edit Category
    </h3>
    <div class="category-form">
        <div class="form-horizontal">
           <div class="form-group">
                <label class="col-md-3">Category Name : </label>
                <div class="col-md-9">
                    <input type="text" class="form-control category-name" placeholder="Type the category name here" value=""/>
                    <input type="hidden" class="hidden-category-id" value="0"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3">Parent Category : </label>
                <div class="col-md-9">
                    <select class="form-control category-name parent-category-dropdown" placeholder="Type the category name here">
                        <option value="0">None</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Add items to this category : </label>
  
            <div class="customized-category-error">
                <div class="alert alert-es-danger" role="alert">
                    <a href="javascript:void(0)" class="alert-link error-message" style="border:none;"></a>
                </div>
            </div>
        </div>
 
        <div class="drag-drop-container">
            <div class="row">
                <div class="col-xs-6 custom-left-panel">
                    <div class="drag-drop-group-list">
                        <div class="category-panel-header">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="category-panel-title">My Category</span>
                                </div>
                            </div>
                        </div>
                        <div class="category-items-holder my-category-container category-items" data-page="1" data-isComplete="false" data-isProcessing="false">
                            <ul class="customCategoryEdit category-product-list product-list">
  
                            </ul>
                            
                            <div class="loader clear" style="text-align:center; display:none; font-size:10px">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-sm.gif" />
                                <br/>
                                Loading more items...
                            </div>
                            <input type="hidden" class="all-loaded-products-ids" value="<?php echo json_encode([]); ?>" />
                        </div>
                    </div>
                </div>  
 
                
                <div class="col-xs-6 all-right-panel">
                    <div class="drag-drop-group-list">
                        <div class="category-panel-header">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="category-panel-title">All Items</span>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="search-category" placeholder="Search product from list...">
                                    <input type="hidden" class="isSearching" value="false">
                                </div>
                            </div>
                        </div>
                        <div class="category-items-holder my-category-container all-items" data-page="1" data-isComplete="false" data-isProcessing="false">
                            <ul class="allItemsEdit ui-helper-reset ui-helper-clearfix all-product-list product-list">
                            </ul>
                            <div class="loader clear" style="text-align:center; display:none; font-size:10px">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/es-loader-3-sm.gif" />
                                <br/>
                                Loading more items...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="my-modal-footer">
        <center>
            <button class="btn btn-default-3 save-category-changes">Save Changes</button>
            <button class="btn btn-default-1 simplemodal-close">Cancel</button> 
        </center>
    </div>
</div>


<div class="overlay-for-waiting-modal" style="">
    <div class="overlay-loader-container-main">

    </div>
     <div class="overlay-loader-container">
        <img src="/assets/images/es-loader-3.gif">
    </div>
</div>
