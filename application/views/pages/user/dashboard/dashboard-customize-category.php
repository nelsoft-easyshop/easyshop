<div class="div-tab">
    <div class="dashboard-breadcrumb">
        <ul>
            <li>Dashboard</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>My Store</li>
            <li class="bc-arrow"><i class="fa fa-angle-right"></i>Store Setup</li>
        </ul>
    </div>
    <div class="div-tab-inner">
        <div class="div-store-setup">
            <p class="panel-setup-title">CATEGORIES</p>
            <div class="div-store-content">
                <div class="current-store-cat">
                    <div class="form-horizontal">
                       <div class="form-group">
                            <label class="col-sm-3 control-label">Current Order : </label>
                            <div class="col-sm-9 col-with-radio">

                                <div class="store-category-view">
	                                <div class="div-cat">Clothing  &amp; Accessories</div>
	                                <div class="div-cat">Jewellery &amp; Watches</div>
	                                <div class="div-cat">Toys, Hobbies &amp; Collectibles</div>
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
                        <div class="col-sm-5 col-with-radio">
                            <ul class="category_sort list new-store-category-draggable ui-sortable">
                        		<li><i class="fa fa-sort"></i>Clothing  &amp; Accessories <i class="icon-edit modal-category-edit pull-right edit-category"></i></li>
                    			<li><i class="fa fa-sort"></i>Jewellery &amp; Watches <i class="icon-edit modal-category-edit pull-right edit-category"></i></li>
                    			<li><i class="fa fa-sort"></i>Toys, Hobbies &amp; Collectibles <i class="icon-edit modal-category-edit pull-right edit-category"></i></li>
                            </ul>
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
                    when someone views your store.
                </p>
            </div>
            <!-- Display this markup if there are no available categories
            <br/>
            <div class="jumbotron no-items">
                <i class="icon-category"></i> No available category
            </div>
            -->
        </div>
        

        <div class="div-store-setup">
            <p class="panel-setup-title">NEW CATEGORY</p>
            <div class="div-store-content">
                <div class="add-store-cat">
                    <button class="btn btn-setting-edit-btn" id="add-category">
                       ADD NEW CATEGORY
                    </button>
                </div>
                <p class="p-note-setting">
                    Vestibulum quis placerat enim. Vestibulum at aliquet nibh, fringilla porta nulla. Cras at sem convallis, convallis magna vitae, porta leo. Phasellus suscipit pulvinar tortor, ac gravida felis sodales et.
                </p>
            </div>
            <!-- Display this markup if there are no available categories
            <br/>
            <div class="jumbotron no-items">
                <i class="icon-category"></i> No available category
            </div>
            -->
        </div>


        <div class="div-store-setup">
            <p class="panel-setup-title">DELETE CATEGORIES</p>
            <div class="div-store-content">
                <div class="delete-store-cat">
                    <div class="form-horizontal">
                       <div class="form-group">
                            <label class="col-sm-3 control-label">Current Categories : </label>
                            <div class="col-sm-9 col-with-radio">
                                <ul class="list-unstyled list-currect-categories">
                                    <li class="checkbox">
                                        <label>
                                            <input type="checkbox" class="checkBox">
                                            Clothing &amp; Accessories
                                        </label>
                                    </li>
                                    <li class="checkbox">
                                        <label>
                                            <input type="checkbox" class="checkBox">
                                            Jewellery &amp; Watches
                                        </label>
                                    </li>
                                    <li class="checkbox">
                                        <label>
                                            <input type="checkbox" class="checkBox">
                                            Toys, Hobbies &amp; Collectibles
                                        </label>
                                    </li>
                                </ul>
                                <div>
                                    <br/>   
                                    <button class="btn btn-setting-cancel-btn" id="btn-edit-store-cat">
                                    Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="p-note-setting">
                    Vestibulum quis placerat enim. Vestibulum at aliquet nibh, fringilla porta nulla. Cras at sem convallis, convallis magna vitae, porta leo. Phasellus suscipit pulvinar tortor, ac gravida felis sodales et
                </p>
            </div>
            <!-- Display this markup if there are no available categories
            <br/>
            <div class="jumbotron no-items">
                <i class="icon-category"></i> No available category
            </div>
            -->
        </div>
    </div>
</div>

<!--For ADD category modal-->
<div class="add-category-modal">
    <h3 class="my-modal-title">
        Add Category
    </h3>
    <div class="category-form">
        <div class="form-horizontal">
           <div class="form-group">
                <label class="col-sm-3">Category Name : </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control"  placeholder="Type the category name here" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Add items to this category : </label>
            <p class="note-category">Vestibulum quis placerat enim. Vestibulum at aliquet nibh,</p>
        </div>
 
        <div class="drag-drop-container">
            <div class="row">
                <div class="col-xs-6">
                    <div class="drag-drop-group-list">
                        <div class="category-panel-header">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="category-panel-title">My Category</span>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="" placeholder="Search product from list...">
                                </div>
                            </div>
                        </div>
                        <div class="category-items-holder my-category-container" >
                            <ul class="customCategory" id="customCategory" >
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="drag-drop-group-list">
                        <div class="category-panel-header">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="category-panel-title">All Items</span>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="" placeholder="Search product from list...">
                                </div>
                            </div>
                        </div>
                        <div class="category-items-holder my-category-container">
                           <ul id="allItems" class="allItems ui-helper-reset ui-helper-clearfix">
                                <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder">
                                        <a href="#" class="icon-move icon-move-to-custom-category pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/apple-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">iPhone6 Black</div>
                                 </li>
                                 <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder">
                                        <a href="#" class="icon-move icon-move-to-custom-category pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/htc-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">HTC One</div>
                                 </li>
                                 <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder">
                                        <a href="#" class="icon-move icon-move-to-custom-category pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/lg-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">LG G3</div>
                                 </li>
                                 <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder">
                                        <a href="#" class="icon-move icon-move-to-custom-category pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/samsung-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">Samsung Galaxy S4</div>
                                 </li>
                                 <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder">
                                        <a href="#" class="icon-move icon-move-to-custom-category pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/samsung-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">Samsung Galaxy S4</div>
                                 </li>
                                 <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder">
                                        <a href="#" class="icon-move icon-move-to-custom-category pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/fossil-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">Fossil Watch</div>
                                 </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="my-modal-footer">
        <center>
            <button class="btn btn-default-3">Add Category</button>
            <button class="btn btn-setting-cancel-btn simplemodal-close">Cancel</button>
        </center>
    </div>
</div>


<!--For EDIT category modal-->
<div class="edit-category-modal">
    <h3 class="my-modal-title">
        Edit Category
    </h3>
    <div class="category-form">
        <div class="form-horizontal">
           <div class="form-group">
                <label class="col-md-3">Category Name : </label>
                <div class="col-md-9">
                    <input type="text" class="form-control" placeholder="Type the category name here" value="Easy Treats"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Add items to this category : </label>
            <p class="note-category">Vestibulum quis placerat enim. Vestibulum at aliquet nibh, fringilla porta nulla. Cras at sem convallis, </p>
        </div>
 
        <div class="drag-drop-container">
            <div class="row">
                <div class="col-md-6">
                    <div class="drag-drop-group-list">
                        <div class="category-panel-header">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="category-panel-title">My Category</span>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="" placeholder="Search product from list...">
                                </div>
                            </div>
                        </div>
                        <div class="category-items-holder my-category-container" >
                            <ul class="customCategory_edit" id="customCategory_edit" >
                                 <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder_edit">
                                        <a href="#" class="icon-move_edit icon-move-to-all-items_edit pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/htc-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">HTC One</div>
                                 </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="drag-drop-group-list">
                        <div class="category-panel-header">
                            <div class="row">
                                <div class="col-md-5">
                                    <span class="category-panel-title">All Items</span>
                                </div>
                                <div class="col-md-7">
                                    <input type="text" class="" placeholder="Search product from list...">
                                </div>
                            </div>
                        </div>
                        <div class="category-items-holder my-category-container">
                           <ul id="allItems_edit" class="allItems_edit ui-helper-reset ui-helper-clearfix">
                                <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder_edit">
                                        <a href="#" class="icon-move_edit icon-move-to-custom-category_edit pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/lg-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">LG G3</div>
                                 </li>
                                 <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder_edit">
                                        <a href="#" class="icon-move_edit icon-move-to-custom-category_edit pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/samsung-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">Samsung Galaxy S4</div>
                                 </li>
                                 <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder_edit">
                                        <a href="#" class="icon-move_edit icon-move-to-custom-category_edit pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/samsung-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">Samsung Galaxy S4</div>
                                 </li>
                                 <li class="ui-widget-content ui-corner-tr">
                                    <span class="icon-holder_edit">
                                        <a href="#" class="icon-move_edit icon-move-to-custom-category_edit pull-right" ></a>
                                    </span>
                                    <div class="category-item-image" style="background: #fff url(/assets/images/products/fossil-p.jpg) center no-repeat; background-size: 90%;" ></div>
                                    <div class="category-item-name">Fossil Watch</div>
                                 </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="my-modal-footer">
        <center>
            <button class="btn btn-default-3">Save Changes</button>
            <button class="btn btn-setting-cancel-btn simplemodal-close">Cancel</button>
        </center>
    </div>
</div>