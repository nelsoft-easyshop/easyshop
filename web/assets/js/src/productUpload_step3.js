/**
 *
 *  Add location on click - adds new select field for delivery location
 *
 *  Shipping summary mouse events - displays edit and delete buttons for 
 *  location vs price fields in Summary Table
 *
 */
$(function(){

  $.modal.defaults.persist = true;

  $('#add_location').on('click',function(){
    var datacount = $('#shiploc_count').val();
    $('#shiploc_count').val(+datacount+1);
    var selecttrnew = $('#shiploc_selectiontbl').find('select[name="shiploc1"]').closest('tr').clone();
    selecttrnew.find('select[name^="shiploc"]')[0].name = "shiploc"+ (+datacount+1);
    selecttrnew.find('input[name^="shipprice"]')[0].name = "shipprice"+ (+datacount+1);
    selecttrnew.append('<td><span class="delete_locrow button">Remove</td>');
    $('#shiploc_selectiontbl').find('tr:last').before('<tr class="newlocrow">' + selecttrnew.html() + '</tr>');
    $('.shipping_table2').animate({scrollTop: $('.shipping_table2').prop("scrollHeight")}, 1000);
  });

  $('#shiploc_selectiontbl').on('click', '.delete_locrow', function(){
    $(this).closest('tr').remove();
    var shipLocCount = $('#shiploc_count');
    var locCount = parseInt(shipLocCount.val());
    shipLocCount.val(locCount-1);
  });

  $('#shiploc_selectiontbl, #shipping_summary').on('keyup', '.shipprice', function(e){
	var price = $.trim($(this).val());
	var newPrice = price.replace(new RegExp(",", "g"), '');
	newPrice = parseFloat(newPrice).toFixed(2);
	
	if( (e.keyCode == 13 || e.which == 13) && $.isNumeric(newPrice)){
		$(this).val( ReplaceNumberWithCommas(newPrice) );
	}else if ( (e.keyCode == 13 || e.which == 13) && !$.isNumeric(newPrice) ) {
		$(this).val('');
	}
  }).on('keypress', '.shipprice', function(e){
	var code = e.keyCode || e.which;
	return ( (code>=48 && code<=57) || code === 46 || code === 44 || code===8 || (code>=37 && code<=40) || ((code === 97 || code === 99 || code === 118) && e.ctrlKey) );
  }).on('blur', '.shipprice', function(){
	var price = $.trim($(this).val());
	var newPrice = price.replace(new RegExp(",", "g"), '');
	newPrice = parseFloat(newPrice).toFixed(2);
	if( $.isNumeric(newPrice) ){
		$(this).val( ReplaceNumberWithCommas(newPrice) );
	}else{
		$(this).val('');
	}
  });
  
  
  /***************		Select all attributes	*************/
  $('#select_all_attr').on('click', function(){
	if(this.checked){
		$('.product_combination').addClass('active');
	} else {
		$('.product_combination').removeClass('active');
	}
	
  });
  
  
  /************ Shipping Preferences *********************/
  $('#shipping_preference').on('click', function(){
	$('#div_shipping_preference').modal({
		containerCss:{
			maxHeight: '500px',
			maxWidth: '990px'
		},
		onShow: function(dialog){
			$('#import_shipping_preference').on('click', function(){
				var headId = $('input[name="shipping_preference"]:checked').val();
				$('tr.newlocrow').remove();
				$('#shiploc_count').val(1);
				var i = 1;
				$.each(shippingPreference[headId], function(locationId,price){
					$('select[name="shiploc'+i+'"]').val(locationId);
					$('input[name="shipprice'+i+'"]').val(parseFloat(price).toFixed(2));
					i++;
					$('#add_location').trigger('click');
				});
				$.modal.close();
			});
		},
		onOpen: function (dialog) {
			dialog.overlay.fadeIn(250, function () {
				dialog.container.slideDown(250, function () {
					dialog.data.fadeIn(250);
				});
			});
		},
		onClose: function(dialog){
			dialog.data.fadeOut(200, function () {
				dialog.container.slideUp(200, function () {
					dialog.overlay.fadeOut(200, function () {
						$.modal.close(); 
					});
				});
			});
		}
	});
  });
  
  $('#div_shipping_preference').on('mouseover', 'p.ship_pref_option', function(){
	$(this).children('span.delete_ship_preference').show();
  })
  .on('mouseleave', 'p.ship_pref_option', function(){
	$(this).children('span.delete_ship_preference').hide();
  });
	
  
});
/********** CLOSE DOCUMENT READY FUNCTION *********/


/**
*
* This function section contains behavioral functions in creating the summary field.
* 
* No data is sent to the server until the submit button is hit
*
* fdata - contains final data to be sent to the server
* displaygroup - temporary object to contain attributes per group(row) in the Shipping Summary Table.
*              - used to easily identify where to insert location vs price if attr(group) already exists in summary table
*
*/
$(function(){
	// located in view fdata, displaygroup, locationgroup, islandLookup
  var divLocWarning = $('#div_locationwarning');
  var spanLocWarning = $('#location_warning');
  
  var spanerror = $('#spanerror');
  var shiplocselectiontbl = $('#shiploc_selectiontbl');
  var hasAttr = parseInt($('#has_attr').val());
  var prdItemId = parseInt($('#product_item_id').val());
  
  /**
   * Add Shipping Details to Summary list on-click event
   */
  $('#add_shipping_details').on('click', function(){
	if(checkIfEdit()){
		alert('Please accept changes in Summary Table.');
		return;
	}
    var hasActive = hasLoc = hasPrice = hasLP = false;
    var noDuplicate = true;
	var shipObj = { 'attr' : {},'loc' : {},'price' : {}, 'disp_attr' : {} };
    var i = parseInt($('#summaryrowcount').val());
	
    //Get Product Attribute Options
	if(hasAttr === 1){
		$('.product_combination.active').each(function(){
		  var attrText = $(this).text();
		  attrText = attrText.replace(/[^\w\s:]/gi, '-');
		  attrText = $.trim(attrText.replace(/\r?\n|\r/g, ''));
		  attrText = attrText.replace(/\s+/g,' ');
		  shipObj.attr[$(this).val()] = attrText;
		  shipObj.disp_attr[$(this).val()] = $(this).val();
		  hasActive = true;
		});
	}
	else if(hasAttr === 0){
		shipObj.attr[prdItemId] = 'All Combinations';
		shipObj.disp_attr[prdItemId] = prdItemId.toString();
		hasActive = true;
	}
    
    //Get Shipping Location Select(s) and corresponding Price
    $('.shiploc').each(function(){
      var selopt = $(this).find('option:selected');
      var price = $(this).parent('td').next('td').children('input[name^="shipprice"]');
      
	  hasPrice = $.trim(price.val()) !== '' ? true : false;
	  hasLoc = selopt.val() != 0 ? true : false;
	  
	  if(hasLoc && hasPrice){
		var priceVal = price.val().replace(new RegExp(",", "g"), '');
		priceVal = parseFloat(priceVal).toFixed(2);
		shipObj.price[selopt.val()] = priceVal;
		shipObj.loc[selopt.val()] = $.trim(selopt.text());
		hasLP = true;
	  }
    });

    //Check for duplicate entry of attr vs location
    jQuery.each(shipObj.attr, function(attrk,attrv){
      jQuery.each(shipObj.loc, function(lock, locv){
        jQuery.each(fdata, function(groupkey,attrObj){
          if(attrk in attrObj){
            if(lock in attrObj[attrk]){
              noDuplicate = false;
            }
          }
        });  
      });
    });
	
	/*******************	Executed on Complete and non-Duplicate Data	********************************/
    if(hasActive && hasLP && noDuplicate){
      var row = $('table#shipping_summary > tbody > tr.cloningfield').clone();
	  row.removeClass('cloningfield');
      row.find('td:first').html('');
      var summaryExists = addDispGroup = false;
      var groupkey = i;

	  // Determine if new display group / display row will be created
      if(i !== 0 ){
        jQuery.each(displaygroup, function(k,shipObjTemp){
		  if(objectCompare(shipObj.disp_attr, shipObjTemp)){
              row = $('table#shipping_summary').find('tr[data-group="' + k + '"]');
              groupkey = k;
              summaryExists = true;
              return;
          }
        });
      }
      else{
        addDispGroup = true;
        $('#summaryrowcount').val(+i+1);
      }

      //Show table and submit button
      if($('#shipping_summary').hasClass('tablehide')){
        $('#shipping_summary').removeClass('tablehide');
        $('#btnShippingDetailsSubmit').removeClass('tablehide');
      }

      //Display location and price
      var nesttable = row.find('table.shiplocprice_summary > tbody');
      var nesttabletr = nesttable.find('tr.cloningfield').clone();
      nesttabletr.removeClass('cloningfield');
      jQuery.each(shipObj.loc, function(k,v){
        nesttabletr.children('td:first').html(v);
        nesttabletr.children('td:last').hide();
        var PriceField = nesttabletr.children('td:nth-child(2)');
        PriceField.attr('data-value', shipObj.price[k]);
        PriceField.html(ReplaceNumberWithCommas(shipObj.price[k]));
        nesttable.append('<tr data-idlocation='+k+' data-groupkey='+groupkey+'>'+nesttabletr.html()+'</tr>');
      });

      //Append new summary details as new row if summary does not exist
      if(!summaryExists){
        addDispGroup = true;
        $('#summaryrowcount').val(+i+1);
        jQuery.each(shipObj.attr, function(arrkey,rawString){
			arr = rawString.split('- ');
			jQuery.each(arr, function(k,v){
				if(v !== ''){
					row.children('td:first').append('<p>' + v + "</p>");
				}
			});
			row.children('td:first').append('<span>&nbsp;</span>');
        });
        $('table#shipping_summary').append('<tr class="tr_shipping_summary" data-group="'+i+'">' + row.html() + '</tr>');
      }

      //Recondition fields and variables
      $('.shiploc').not('[name="shiploc1"]').each(function(){
        $(this).closest('tr').remove();
      });
      $('.product_combination').each(function(){
        $(this).removeClass('active');
      });
      shiplocselectiontbl.find('select[name="shiploc1"]').val(0);
      shiplocselectiontbl.find('input[name="shipprice1"]').val('');
      $('#shiploc_count').val(1);
      
      if(!(groupkey in fdata)){
        fdata[groupkey] = {};
      }

      //Push data to fdata object - to be sent to server
      jQuery.each(shipObj.attr, function(attrk, attrv){
        if(!(attrk in fdata[groupkey])){
          fdata[groupkey][attrk] = {};
        }
        jQuery.each(shipObj.loc, function(lock, locv){
          if( !( lock in fdata[groupkey][attrk] ) ){
			fdata[groupkey][attrk][lock] = {};
		  }
		  fdata[groupkey][attrk][lock] = shipObj.price[lock];
		  
		  if(jQuery.inArray(lock, locationgroup) === -1){
			locationgroup.push(parseInt(lock));
		  }
        });
      });

      if(addDispGroup){
	    displaygroup[i] = shipObj.disp_attr;
	  }
      
	  updateLocationError();
	  
	  $('#select_all_attr').attr('checked', false);
    }//close hasloc hasactive hasprice
	else{
		var duperror = '';
		var alerterror = '';
		if(!hasActive || !hasLP){
			alerterror = 'Please specify the following: ';
			var counter = 0;
			if(!hasActive){
				alerterror += '-attribute ';
				counter++;
			}
			if(!hasLoc){
				alerterror += '-location ';
				counter++;
			}
			if(!hasPrice){
				alerterror += '-price ';
				counter++;
			}
			for(var i=0;i<counter;i++){
				if(counter === 1 || i==0){
					alerterror = alerterror.replace('-', '');
				}
				else if( i==counter-1 ){
					alerterror = alerterror.replace('-', 'and ');
				}
				else{
					alerterror = alerterror.replace('-', ', ');
				}
			}
			alerterror += '<br>';
		}
		
		if(!noDuplicate){
			duperror = 'Location already used for selected attribute.'
		}
		
		alert(alerterror + duperror);
		return;
	}
  });//close on click of adding ship details to summary
  
  
  // Check Shipping Location if 3 major islands are covered
  function updateLocationError(){
	spanLocWarning.html("");
	var incLocation = false;
	$.each(islandLookup, function(key,value){
		if( jQuery.inArray(value,locationgroup) == -1){
			incLocation = true;
			switch(value){
				case 2:
					spanLocWarning.append('Luzon ');
					break;
				case 3:
					spanLocWarning.append('Visayas ');
					break;
				case 4:
					spanLocWarning.append('Mindanao ');
					break;
				default:
					spanLocWarning.html("");
					break;
			}
		}
	});
	if(incLocation){
		divLocWarning.show();
	}else{
		divLocWarning.hide();
	}
  }
  
  /**
  * Submit Handler function
  */
  $('#btnShippingDetailsSubmit').on('click', function(){
	
	if(checkIfEdit()){
		alert('Please accept changes in Summary Table.');
		return;
	}
	
	// Check if all attribute combinations have mapped locations
	// ProductItemId located in view
	var hasDetail = true;
	jQuery.each(ProductItemId, function(k,v){
		if(hasDetail){
			jQuery.each(displaygroup, function(k2,v2){
				hasDetail = false;
				if( displaygroup[k2].hasOwnProperty(v) ){
					hasDetail = true;
					return false;
				}
			});
		}		
	});
	
	if(!hasDetail){
		alert('Please add shipping details for all combinations.');
		return false;
	}
	
    if(getObjectSize(fdata) > 0){
	  var csrftoken = $("meta[name='csrf-token']").attr('content');
      var csrfname = $("meta[name='csrf-name']").attr('content');
	  //var productitemid = $('#json_id_product_item').val();
	  var productitemidlist = ProductItemId;
	  var productid = parseInt($('#prod_h_id').val());
	  var loadingimg = $(this).siblings('img.loading_img_step3');
	  var thisbtn = $(this);
	  
	  thisbtn.hide();
	  loadingimg.show();

	  $.post(config.base_url+'sell/shippinginfo', {fdata : fdata, csrfname : csrftoken, productitemid : productitemidlist, productid : productid}, function(data){
		loadingimg.hide();
        thisbtn.val('Please wait');
		thisbtn.show();		
		if(data == 1){
            
            if(isMobile()){
                $('#nonmodal_preview').submit();
            }
            else{
                $.post(config.base_url+'productUpload/previewItem', {p_id: productid, csrfname : csrftoken}, function(data){
                $('#previewProduct').html(data);
                $('#tabs').tabs();
                $('#previewProduct').dialog({
                    width: 1100,
                    height: 500,
                    autoOpen: false,
                    title: "Review your listing",
                    modal: true,
                    closeOnEscape: false,
                    draggable: false,
                    buttons: [
                        {
                            text: "Edit",
                            "class": 'orange_btn_preview',
                            click: function() {
                                $(this).dialog("close");
                            }
                        },
                        {
                            text: "Finish",
                            "class": 'orange_btn_preview',
                            click: function() {
                                
                                var account_name = $('#deposit_acct_name').val();
                                var bank_name = $('#bank_name').val();
                                var account_no = $('#deposit_acct_no').val();
                                var bank_list = $('#bank_list').val();
                                var prod_billing_id = parseInt($('#prod_billing_id').val(),10);
                                var cod_only = ((prod_billing_id === 0)&&($('#allow_cashondelivery').is(':checked')))?true:false;
                                var valid = true;
                            
                                if(!cod_only){
                                    if($.trim(account_name) === ''){
                                        validateRedTextBox('#deposit_acct_name');
                                        valid = false;
                                    }
                                    if($.trim(account_no) === ''){
                                        validateRedTextBox('#deposit_acct_no');
                                        valid = false;
                                    }
                                    if(parseInt(bank_list,10) === 0){
                                        validateRedTextBox('#bank_list');
                                        valid = false;
                                    }
                                    if(!valid){
                                        return false;
                                    }
                                }
                              
                                if((prod_billing_id === 0)&&(!cod_only)){
                                    jQuery.ajax({
                                        type: "POST",
                                        url: config.base_url + 'memberpage/billing_info', 
                                        data: "bi_payment_type=Bank&bi_bank="+bank_list+"&bi_acct_no="+account_no+"&bi_acct_name="+account_name+"&"+csrfname+"="+csrftoken, 
                                        success: function(response) {
                                                var obj = JSON.parse(response);
                                                console.log(obj.e);
                                                if((parseInt(obj.e,10) == 1) && (obj.d == 'success')){
                                                    var new_id = parseInt(response,10);
                                                    $('#prod_billing_id').val(new_id);
                                                    $('#step4_form').submit();
                                                }else if((parseInt(obj.e,10) == 0) && (obj.d == 'duplicate')){
                                                    alert('You are already using this account number.');
                                                }else{
                                                    alert('Something went wrong please try again later.');
                                                }
                                            }
                                    });
                                }
                                else{
                                    $('#step4_form').submit();
                                }
                            }
                        },
                    ],
                    show: {
                        effect: "fade",
                        duration: 600
                    },
                      hide: {
                        effect: "fade",
                        duration: 400
                    }
                });
                $('#previewProduct').dialog('open');
                
                $('#prod_billing_id').val( $('#billing_info_id').val());
                $('#allow_cod').prop('checked', false);
                $('#btnShippingDetailsSubmit').val('SUBMIT');
            });
            }
		}
		else if(data == 0){
			alert('An error was encountered. Please add shipping details for all attribute combinations.');
		}
		else if(data == 2){
			alert('An error was encountered. Database data mismatch!');
		}
      });
    }else{
		alert('You have no entries in the shipping summary list.');
	}
  });

  /**
   * On change event for Location select field
   */
  $('#shiploc_selectiontbl').on('change', '.shiploc', function(){
    var currval = $(this).find('option:selected').val();
    var thistr = $(this).closest('tr');
    var hasDuplicate = false;
	spanerror.hide();
	
	//Check if same location is selected among its select siblings
    $('.shiploc').not(this).each(function(){
      var otherval = $(this).find('option:selected').val();
      if(currval === otherval){
        hasDuplicate = true;
        return;
      }
    });
	
	//Check if location already used for selected attribute
	if(hasAttr === 1){
		$('.product_combination.active').each(function(){
		  var attrk = $(this).val();
		  jQuery.each(fdata, function(groupkey,attrObj){
			if(attrk in attrObj){
			  if(currval in attrObj[attrk]){
				hasDuplicate = true;
				return;
			  }
			}
		  });
		});
	} else {
		jQuery.each(fdata, function(groupkey,attrObj){
			if(prdItemId in attrObj){
			  if(currval in attrObj[prdItemId]){
				hasDuplicate = true;
				return;
			  }
			}
		});
	}
    
    if(hasDuplicate){
      thistr.find('input[name^="shipprice"]').val('');
      $(this).val(0);
	  spanerror.show();
	  thistr.effect('pulsate', {times:3}, 800);
    }

  });

  /**
   * On click event for Attribute Selection
   */ 
  $('.product_combination').on('click', function(){

	spanerror.hide();

    if($(this).hasClass('active')){
      $(this).removeClass('active');
    }
    else{
      var attrk = $(this).val();
      $(this).addClass('active');

      jQuery.each(fdata, function(groupkey,attrObj){
        if(attrk in attrObj){
          $('.shiploc').each(function(){
            var lock = $(this).find('option:selected').val();
            var thistr = $(this).closest('tr');

            if(lock in attrObj[attrk]){
              thistr.find('input[name^="shipprice"]').val('');
              //thistr.append(spanerror);
			  spanerror.show();
			  thistr.effect('pulsate', {times:3}, 800);
              $(this).val(0);
            }
          });
        }
      });
    }

  });

  /**
  * Shipping Summary button functions
  */ 
  $('#shipping_summary').on('click', '.delete_summaryrow', function(){
    var group = $(this).closest('tr').attr('data-group');
	var locationtr = $(this).closest('td').prev('td').find('tr:not(".cloningfield")');
    $(this).closest('tr').remove();
    delete fdata[group];
    delete displaygroup[group];
	locationtr.each(function(k,v){
		var remove = parseInt($(v).attr('data-idlocation'));
		locationgroup = jQuery.grep(locationgroup, function(value){
			return value != remove;
		});
	});
	updateLocationError();
    hideTable();
  })
  .on('click', '.edit_summaryrow', function(){
    $(this).hide();
    $(this).siblings('.edit_del').hide();
    $(this).siblings('.accept_cancel').show();
	$(this).closest('tr').addClass('inedit');
	
    $(this).parent('td').prev('td').find('tr').each(function(){
      var PriceField = $(this).find('td:nth-child(2)');
	  var PriceValue = PriceField.attr('data-value');
      PriceField.html('<input type="text" class="shipprice" value="'+PriceValue+'">');
	  $(this).children('td:last').show();
    });
  })
  .on('click', '.accept_summaryrow', function(){
    var PriceLocRows = $(this).parent('td').prev('td').find('tr:not(".cloningfield")');
    var isFilled = true;

    PriceLocRows.each(function(){
      var inputPriceField = $(this).find('td:nth-child(2)').find('input');
      var newPrice = $.trim(inputPriceField.val()).replace(new RegExp(",", "g"), '');
	  newPrice = parseFloat(newPrice).toFixed(2);
	  var selectCourierField = $(this).find('td:nth-child(3)').find('select');

      if(isNaN(newPrice)){
        inputPriceField.effect('pulsate',{times:3},800);
        isFilled = false;
        return;
      }
    });

    if(isFilled){
      $(this).hide();
      $(this).siblings('.edit_del').show();
      $(this).siblings('.accept_cancel').hide();
	  $(this).closest('tr').removeClass('inedit');
	  
      PriceLocRows.each(function(){
        var PriceField = $(this).find('td:nth-child(2)');
        var newPrice = $.trim(PriceField.find('input').val()).replace(new RegExp(",", "g"), '');
		newPrice = parseFloat(newPrice).toFixed(2);
        var groupkey = $(this).attr('data-groupkey');
        var idlocation = $(this).attr('data-idlocation');
		
        PriceField.attr('data-value', newPrice);
        PriceField.html(ReplaceNumberWithCommas(newPrice));
        
		$(this).find('td:last').hide();
		
        jQuery.each(fdata[groupkey], function(attrk, attrObj){
		  //attrObj[idlocation]['price'] = newPrice;
		  attrObj[idlocation] = newPrice;
        });
		
      });
    }
  })
  // Delete button per Location VS Price
  .on('click', '.delete_priceloc', function(){
    var parentTr = $(this).closest('tr');
    var groupkey = parentTr.attr('data-groupkey');
    var idlocation = parentTr.attr('data-idlocation');
    jQuery.each(fdata[groupkey], function(attrk, attrObj){
      delete attrObj[idlocation];
    });
	locationgroup = jQuery.grep(locationgroup, function(value){
		return value!=idlocation;
	});
	updateLocationError();
    if(parentTr.siblings('tr:not(".cloningfield")').length === 0){
      parentTr.parent().closest('tr').remove();
      delete fdata[groupkey];
      delete displaygroup[groupkey];
      hideTable();
      $('.edit_del').show();
      $('.accept_cancel').hide();
    }
    parentTr.remove();
  });

  /**
	* Function to hide Summary Table and Submit Button.
	* Checks if DisplayGroup Object is empty - meaning nothing displayed - before execution
	*/
	function hideTable(){
		if(getObjectSize(displaygroup) === 0){
		  $('#shipping_summary').addClass('tablehide');
		  $('#btnShippingDetailsSubmit').addClass('tablehide');
		  divLocWarning.hide();
		  spanLocWarning.html("");
		}
	}
  
  
	/*
	 *	Add shipping preferences
	 */
    $('#shipping_summary').on('click','.add_ship_preference', function(){
		var groupkey = $(this).closest('tr.tr_shipping_summary').attr('data-group');
		var preferenceData = {};
		var preferenceName = "";
		var csrftoken = $("meta[name='csrf-token']").attr('content');
		var csrfname = $("meta[name='csrf-name']").attr('content');
		
		// Get location vs price details for first attr array, since data is same for all attr arrays
		$.each(fdata[groupkey], function(attrk,attrarr){
			preferenceData = attrarr;
			return false;
		});
		
		$('#dialog_preference_name').dialog({
			height: 180,
			autoOpen: false,
			title: "Enter Preference name",
			modal: true,
			closeOnEscape: false,
			draggable: false,
			buttons:[
				{
					text: "Ok",
					click: function(){
						var namefield = $('#preference_name');
						var thisdialog = $(this);
						var cloningfield = $('#div_shipping_preference p.cloningfield');
						preferenceName = namefield.val();
						
						if( ($.trim(preferenceName)).length > 0){
							$('button.ui-button').attr('disabled',true);
							namefield.attr('disabled',true);
							namefield.siblings('img.loading').show();
							
							$.post(config.base_url+'productUpload/step3_addPreference',{data:preferenceData, name:preferenceName, csrfname:csrftoken},function(data){
								namefield.siblings('img.loading').hide();
								namefield.attr('disabled',false);
								$('button.ui-button').attr('disabled',false);
								
								try{
									var obj = jQuery.parseJSON(data);	
								}
								catch(e){
									alert('An error was encountered while processing your data. Please try again later.');
									window.location.reload(true);
									return false;
								}
								
								if( obj['result'] === 'success' ){
									// Recreate shippingpreference variable data
									shippingPreference = obj['shipping_preference'];
									// Clear preference DIV display									
									$('#div_shipping_preference div.radio_container').children().not('p.cloningfield').remove();
									
									// Create new preference display contents
									$.each(shippingPreference['name'], function(headId,name){
										var thisfield = cloningfield.clone();
										
										thisfield.removeClass('cloningfield');
										thisfield.find('input').attr('value', headId);
										thisfield.find('label').html(name);
										$('#div_shipping_preference div.radio_container').append(thisfield);
									});
								}else{
									alert(obj['error']);
								}
								
								thisdialog.dialog("close");
							});
						} else {
							namefield.effect('pulsate', {times:3}, 800);
						}
					}
				},
				{
					text: "Cancel",
					click: function(){
						$(this).dialog("close");
					}
				},
			],
			close: function(){
				$('#dialog_preference_name input[name="preference_name"]').val('');
			}
		});
		
		$('#dialog_preference_name').dialog("open");
		
	});
	
	/*
	 *	Delete Shipping Preference
	 */
	$('#div_shipping_preference').on('click','.delete_ship_preference',function(){
		var headId = parseInt($(this).siblings('input[name="shipping_preference"]').val());
		var csrftoken = $("meta[name='csrf-token']").attr('content');
		var csrfname = $("meta[name='csrf-name']").attr('content');
		
		var thisspan = $(this);
		
		var r=confirm("Confirm delete?");
		if (r==true){
			$.post(config.base_url+'productUpload/step3_deletePreference', {head:headId, csrfname:csrftoken}, function(data){
				try{
					var obj = jQuery.parseJSON(data);	
				}
				catch(e){
					alert('An error was encountered while processing your data. Please try again later.');
					window.location.reload(true);
					return false;
				}
				
				if( obj['result'] === 'success' ){
					if( thisspan.closest('p').siblings('p:not(".cloningfield")').length === 0 ){
						$('#div_shipping_preference div.radio_container').append('<span><strong>You have no shipping preference entry to display.</strong></span>')
					}
					thisspan.closest('p').remove();
				}else{
					alert( obj['error'] );
				}
			});
		}
		
	});
    
    
    
});
/********** CLOSE DOCUMENT READY FUNCTION WITH DATA variables *********/

/**
 * Compares if two objects are identical
 */
function objectCompare(o1, o2){
  for(var p in o1){
    if(o1[p] !== o2[p]){
      return false;
    }
  }
  for(var p in o2){
    if(o1[p] !== o2[p]){
      return false;
    }
  }
  return true;
}

/**
 * Get object size
 */
function getObjectSize(obj) {
    var len = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) len++;
    }
    return len;
};

/**
 *	Check if any field in summary table is in edit mode
 */
function checkIfEdit(){
	var hasEdit = false;
	$('#shipping_summary').find('tr.tr_shipping_summary').each(function(){
		if( $(this).hasClass('inedit') ){
			hasEdit = true;
			return;
		}
	});
	return hasEdit;
}

/**
* Function to handle display of Price Value
**/
function ReplaceNumberWithCommas(thisnumber){
    //Seperates the components of the number
    var n = thisnumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
}


    
$(function(){
    $('#step2_link').on('click', function(){
        $('#edit_step2').submit();
    });
    
    $('#step1_link').on('click', function(){
        $('#edit_step1').submit();
    });
});


/*
 *	Step 3 tooltip / tutorial section
 */
$(function(){

	$('#div_tutShippingLoc .paging:not(:first)').hide();
	
	$('#tutShippingLoc').on('click',function(){
		$('#div_tutShippingLoc').modal({
			onShow: function(){
				var modal = this;
				$('#paging_tutShippingLoc').jqPagination({
					paged: function(page) {
						$('#div_tutShippingLoc .paging').hide();
						$($('#div_tutShippingLoc .paging')[page - 1]).show();
						modal.setPosition();
					},
					page_string:'{current_page} of {max_page}'
				});
				$('#paging_tutShippingLoc').jqPagination('option', 'current_page', 1);
			},
            onOpen: function (dialog) {
                dialog.overlay.fadeIn(250, function () {
                    dialog.container.slideDown(250, function () {
                        dialog.data.fadeIn(250);
                    });
                });
            },
			onClose: function(dialog){
				$('#paging_tutShippingLoc').jqPagination('destroy');
				dialog.data.fadeOut(200, function () {
                    dialog.container.slideUp(200, function () {
                        dialog.overlay.fadeOut(200, function () {
                            $.modal.close(); 
                        });
                    });
                });
			}
		});
	});
});





function isMobile(){
    if(screen.width < 500 ||
     navigator.userAgent.match(/Android/i) ||
     navigator.userAgent.match(/webOS/i) ||
     navigator.userAgent.match(/iPhone/i) ||
     navigator.userAgent.match(/iPod/i)) {
        return true;
     }
     else{
        return false;
     }
}
