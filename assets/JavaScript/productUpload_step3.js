/**
 *
 *  Add location on click - adds new select field for delivery location
 *
 *  Shipping summary mouse events - displays edit and delete buttons for 
 *  location vs price fields in Summary Table
 *
 */
$(function(){
  $('#add_location').on('click',function(){
    var datacount = $('#shiploc_count').val();
    $('#shiploc_count').val(+datacount+1);
    var selecttrnew = $('#shiploc_selectiontbl').find('select[name="shiploc1"]').closest('tr').clone();
    selecttrnew.find('select[name^="shiploc"]')[0].name = "shiploc"+ (+datacount+1);
    selecttrnew.find('input[name^="shipprice"]')[0].name = "shipprice"+ (+datacount+1);
	//  selecttrnew.find('td.samelocerror').remove();
    selecttrnew.append('<td><span class="delete_locrow button">Remove</td>');
    $('#shiploc_selectiontbl').find('tr:last').before('<tr class="newlocrow">' + selecttrnew.html() + '</tr>');
    $('.shipping_table2').animate({scrollTop: $('.shipping_table2').prop("scrollHeight")}, 1000);
  });

  $('#shiploc_selectiontbl').on('click', '.delete_locrow', function(){
    $(this).closest('tr').remove();
    var shipLocCount = $('#shiploc_count');
    var locCount = parseInt(shipLocCount.val());
    shipLocCount.val(locCount-1);
  })
  .on('keypress', '.shipprice', function(e){
	//console.log(e);
	var code = e.keyCode || e.which;
	return ( (code>=48 && code<=57) || code === 46 || code === 44 || code===8 || (code>=37 && code<=40) || ((code === 97 || code === 99 || code === 118) && e.ctrlKey) );
  });
  
  $('#shipping_summary').on('keypress', '.shipprice', function(e){
	//console.log(e);
	var code = e.keyCode || e.which;
	return ( (code>=48 && code<=57) || code === 46 || code === 44 || code===8 || (code>=37 && code<=40) || ((code === 97 || code === 99 || code === 118) && e.ctrlKey) );
  });
  
  
  $('#shiploc_selectiontbl, #shipping_summary').on('keyup', '.shipprice', function(e){
	var price = $.trim($(this).val());
	if( (e.keyCode == 13 || e.which == 13) && price != ''){
		var newPrice = price.replace(new RegExp(",", "g"), '');
		newPrice = parseFloat(newPrice).toFixed(2);
		$(this).val( ReplaceNumberWithCommas(newPrice) );
	}
  }).on('blur', '.shipprice', function(){
	var price = $.trim($(this).val());
	if( price != '' ){
		var newPrice = price.replace(new RegExp(",", "g"), '');
		newPrice = parseFloat(newPrice).toFixed(2);
		$(this).val( ReplaceNumberWithCommas(newPrice) );
	}
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
  var fdata = jQuery.parseJSON($('#json_fdata').val());
  var displaygroup = jQuery.parseJSON($('#json_displaygroup').val());
  var locationgroup = jQuery.parseJSON($('#json_locationgroup').val());
  var islandLookup = jQuery.parseJSON($('#json_islandlookup').val());
  var divLocWarning = $('#div_locationwarning');
  var spanLocWarning = $('#location_warning');
  
  //var spanerror = '<td class="error red samelocerror">Unable to select same location for same attribute</td>';
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
    var hasActive = hasLoc = hasPrice = hasLPC = false;
    var noDuplicate = true;
	var shipObj = { 'attr' : {},'loc' : {},'price' : {}, 'disp_attr' : {} };
    var i = parseInt($('#summaryrowcount').val());
	var alerterror = 'Please select: ';
	
    //Get Product Attribute Options
	if(hasAttr === 1){
		$('.product_combination.active').each(function(){
		  var attrText = $(this).text();
		  attrText = attrText.replace(/[^\w\s]/gi, '-');
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
	  
	  //if(hasLoc && hasPrice && hasCourier){
	  if(hasLoc && hasPrice){
		var priceVal = price.val().replace(new RegExp(",", "g"), '');
		priceVal = parseFloat(priceVal).toFixed(2);
		shipObj.price[selopt.val()] = priceVal;
		shipObj.loc[selopt.val()] = $.trim(selopt.text());
		hasLPC = true;
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
	
	/*******************	Executed on Complete Data	********************************/
    if(hasActive && hasLPC && noDuplicate){
      var row = $('table#shipping_summary > tbody > tr.cloningfield').clone();
	  row.removeClass('cloningfield');
      row.find('td:first').html('');
      var summaryExists = addDispGroup = false;
      var groupkey = i;

	  // Determine if new display group / display row will be created
      if(i !== 0 ){
        jQuery.each(displaygroup, function(k,shipObjTemp){
          //if(objectCompare(shipObj.attr, shipObjTemp)){
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
		  //fdata[groupkey][attrk][lock]['price'] = shipObj.price[lock];
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
    }//close hasloc hasactive hasprice
	else{
		var counter = 0;
		if(!hasActive){
			alerterror += '-Attribute ';
			counter++;
		}
		if(!hasLoc){
			alerterror += '-Location ';
			counter++;
		}
		if(!hasPrice){
			alerterror += '-Price ';
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
		alert(alerterror);
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
	var ProductItemId = jQuery.parseJSON($('#json_id_product_item').val());
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
		alert('Please add shipping details for all attribute combinations.');
		return false;
	}
	
    if(getObjectSize(fdata) > 0){
	  var csrftoken = $("meta[name='csrf-token']").attr('content');
      var csrfname = $("meta[name='csrf-name']").attr('content');
	  var productitemid = $('#json_id_product_item').val();
	  var productid = parseInt($('#prod_h_id').val());
	  var loadingimg = $(this).siblings('img.loading_img');
	  var thisbtn = $(this);
	  
	  thisbtn.hide();
	  loadingimg.show();

	  $.post(config.base_url+'sell/shippinginfo', {fdata : fdata, csrfname : csrftoken, productitemid : productitemid, productid : productid}, function(data){
		loadingimg.hide();
		thisbtn.show();		
		if(data == 1){
            
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
                                var prod_billing_id = $('#prod_billing_id').val();
                                
                                var valid = true;
                            
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
                                if(parseInt(prod_billing_id,10) === 0){
                                    jQuery.ajax({
                                        type: "POST",
                                        url: config.base_url + 'memberpage/billing_info', 
                                        data: "express=true&bi_payment_type=Bank&bi_bank="+bank_list+"&bi_acct_no="+account_no+"&bi_acct_name="+account_name+"&"+csrfname+"="+csrftoken, 
                                        success: function(response) {
                                                if(!response){
                                                    alert('We are having a problem right now. Refresh the page to try again.');
                                                }
                                                else{
                                                    var new_id = parseInt(response,10);
                                                    $('#prod_billing_id').val(new_id);
                                                    $('#step4_form').submit();
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
                });
                $('#previewProduct').dialog('open');
    
                $('#prod_billing_id').val( $('#billing_info_id').val());
                $('#allow_cod').prop('checked', false);
                
            });
		}
		else if(data == 0){
			alert('An error was encountered. Please add shipping details for all attribute combinations.');
		}
      });
    }else{
		alert('No shipping details set.');
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
	
    //$(this).parent('td').siblings('td.samelocerror').remove();
	
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
	  /*if(thistr.hasClass('newlocrow')){
		thistr.children('td:last').before(spanerror);
	  } else {
		thistr.append(spanerror);
	  }*/
    }

  });

  /**
   * On click event for Attribute Selection
   */ 
  $('.product_combination').on('click', function(){

    //$('td.samelocerror').remove();
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


function validateRedTextBox(idclass)
{
  $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
    "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
    "box-shadow": "0px 0px 2px 2px #FF0000"});
} 

function validateWhiteTextBox(idclass)
{
  $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
    "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
    "box-shadow": "0px 0px 2px 2px #FFFFFF"});
}
    
$(function(){
    $('#step2_link').on('click', function(){
        $('#edit_step2').submit();
    });
    
    $('#step1_link').on('click', function(){
        $('#edit_step1').submit();
    });
});

$(document).ready(function(){

    
    $('#previewProduct').on('click', '.deposit_edit', function(){
        $('#temp_deposit_acct_name').val($('#deposit_acct_name').val());
        $('#temp_deposit_acct_no').val($('#deposit_acct_no').val());
        $('#temp_bank_list').val($('#bank_list').val());
        $('#temp_bank_name').val($('#bank_name').val());

        $('#deposit_acct_name').attr('readonly', false);
        $('#deposit_acct_no').attr('readonly', false);
        $('#bank_list').attr('disabled', false);
        $('.deposit_edit').hide();
        $('.deposit_update').show();
        $('.deposit_cancel').show();
    });
    
    $('#previewProduct').on('click', '.deposit_update', function(){
        var account_name = $('#deposit_acct_name').val();
        var bank_name = $('#bank_name').val();
        var account_no = $('#deposit_acct_no').val();
        var bank_list = $('#bank_list').val();
        var billing_id = $('#billing_info_id').val();
        var selected = $('#deposit_info [value="'+billing_id+'"]');
        var valid = true;
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
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

        jQuery.ajax({
            type: "POST",
            url: config.base_url + 'memberpage/billing_info_u', 
            data: "bi_id="+billing_id+"&bi_acct_name="+account_name+"&bi_acct_no="+account_no+"&bi_bank="+bank_list+"&"+csrfname+"="+csrftoken, 
            success: function(response) {
                if(!response){
                    alert('We are having a problem right now. Refresh the page to try again.');
                }
                else{
                    selected.data('bankid',bank_list);
                    selected.data('acctname',account_name);
                    selected.data('acctno',account_no);
                    selected.data('bankname',bank_name);
                    selected.text('Bank: '+bank_name+' - '+ account_name);
                }
            }
        });
        $('#deposit_acct_name').attr('readonly', true);
        $('#deposit_acct_no').attr('readonly', true);
        $('#bank_list').attr('disabled', true);
        $('.deposit_edit').show();
        $('.deposit_update').hide();
        $('.deposit_cancel').hide();
    });
    
    $('#previewProduct').on('click', '.deposit_save', function(){
        var account_name = $('#deposit_acct_name').val();
        var bank_name = $('#bank_name').val();
        var account_no = $('#deposit_acct_no').val();
        var bank_list = $('#bank_list').val();
        var valid = true;
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
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
        
        jQuery.ajax({
            type: "POST",
            url: config.base_url + 'memberpage/billing_info', 
            data: "express=true&bi_payment_type=Bank&bi_bank="+bank_list+"&bi_acct_no="+account_no+"&bi_acct_name="+account_name+"&"+csrfname+"="+csrftoken, 
            success: function(response) {
                if(!response){
                    alert('We are having a problem right now. Refresh the page to try again.');
                }
                else{
                    var new_option = $(document.createElement( "option" ));
                    new_option.data('bankname',bank_name);
                    new_option.data('bankid',bank_list);
                    new_option.data('acctname',account_name);
                    new_option.data('acctno',account_no);
                    new_option.text('Bank: '+bank_name+' - '+ account_name);
                    var new_id = parseInt(response,10);
                    new_option.val(new_id);
                    new_option.insertBefore($('#deposit_info').find(':selected'));
                    $('#deposit_info').find(':selected').prop('selected', false);
                    $('#deposit_info').find('option[value = "'+parseInt(response,10)+'"]').prop('selected', true);
                    $('#prod_billing_id').val(new_id);
                }
            }
        });
        $('#deposit_acct_name').attr('readonly', true);
        $('#deposit_acct_no').attr('readonly', true);
        $('#bank_list').attr('disabled', true);
        $('.deposit_edit').show();
        $('.deposit_save').hide();
        $('.deposit_update').hide();
        $('.deposit_cancel').hide();
    });
    
    $('#previewProduct').on('click', '.deposit_cancel', function(){
        $('#deposit_acct_name').val($('#temp_deposit_acct_name').val());
        $('#deposit_acct_no').val($('#temp_deposit_acct_no').val());
        $('#bank_list').val($('#temp_bank_list').val());
        $('#bank_name').val($('#temp_bank_name').val());
        validateWhiteTextBox('#deposit_acct_name');
        validateWhiteTextBox('#deposit_acct_no');
        validateWhiteTextBox('#bank_list');
        $('#deposit_acct_name').attr('readonly', true);
        $('#deposit_acct_no').attr('readonly', true);
        $('#bank_list').attr('disabled', true);
        $('.deposit_edit').show();
        $('.deposit_update').hide();
        $('.deposit_cancel').hide();
    });

    $('#previewProduct').on('change','#deposit_info',function(){
        $('.deposit_update').hide();
        $('.deposit_cancel').hide();
        var selected = $('#deposit_info').find(":selected");
        $this = $(this);
        validateWhiteTextBox('#deposit_acct_name');
        validateWhiteTextBox('#deposit_acct_no');
        validateWhiteTextBox('#bank_list');
        
        if(parseInt(selected.val(),10) !== 0){
            $('#deposit_acct_name').attr('readonly', true);
            $('#deposit_acct_no').attr('readonly', true);
            $('#bank_list').attr('disabled', true);
            $('#billing_info_id').val( $this.val());
            $('#deposit_acct_name').val(selected.data('acctname'));
            $('#deposit_acct_no').val(selected.data('acctno'));
            $('#bank_name').val(selected.data('bankname'));
            $('#bank_list').find(':selected').prop('selected', false);
            $('#bank_list').find('option[value = "'+selected.data('bankid')+'"]').prop('selected', true);
            $('.deposit_edit').show();
            $('.deposit_save').hide();
            $('#prod_billing_id').val($this.val());
        }
        else{
            $('#deposit_acct_name').val('');
            $('#deposit_acct_name').attr('readonly', false);
            $('#deposit_acct_no').val('');
            $('#deposit_acct_no').attr('readonly', false);
            $('#billing_info_id').val(0);
            $('#prod_billing_id').val(0);
            $('#bank_name').val('');
            var default_option = $('#bank_list').find('option[value = "0"]');
            default_option.prop('selected', true);
            $('#bank_list').attr('disabled', false);
            $('.deposit_save').show();
            $('.deposit_edit').hide();
        }
    });
    
    $('#previewProduct').on('change','#bank_list',function(){
        var selected = $('#bank_list').find(":selected");
        $('#bank_name').val(selected.text());
    });
    
    $(document).on('keypress',"#deposit_acct_name,#deposit_acct_no",function () {
        var id = $(this).attr('id');
        validateWhiteTextBox("#"+id);
    });

    $(document).on('change',"#bank_list",function () {
        var id = $(this).attr('id');
        validateWhiteTextBox("#"+id);
    });
    
    $(document).on('change',"#allow_cashondelivery",function () {
        var $this = $(this);
        if($this.is(':checked')){
            $('#allow_cod').prop('checked', true);
        }
        else{
            $('#allow_cod').prop('checked', false);
        }
    });
    
});
 

