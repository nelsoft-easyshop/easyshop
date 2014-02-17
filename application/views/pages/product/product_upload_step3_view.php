<div class="wrapper">


  <div class="clear"></div>

  <div class="tab_list">
    <!-- <p><a href="">Iam a Buyer</a></p> -->
    <p class="active"><a href="">Iam a Seller</a></p>
  </div>
  <div class="clear"></div>
  <div class="seller_product_content">
    <div class="top_nav">
      <ul>
        <li>
            <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_signup.png" alt="signup"><br />
            <span>Account Sign-in</span>
          </a>
          -->
        </li>
        <li>
            <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_shop.png" alt="shop"><br />
            <span>Whant to Shop</span>
          </a>
          -->
        </li>
        <li>
          <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_setup.png" alt="setup"><br />
            <span>Shop exam and set up shop</span>
          </a>
          -->
        </li>
        <li>
          <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_publish.png" alt="publish"><br />
            <span>Published Baby</span>
          </a>
          -->
        </li>
        <li>
          <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_delivery.png" alt="delivery"><br />
            <span>Delivery Operation</span>
          </a>
          -->
        </li>
        <li>
          <!--
          <a href="">
            <img src="<?=base_url()?>assets/images/img_ratings.png" alt="ratings"><br />
            <span>Ratings &amp; Withdrawals</span>
          </a>
          -->
        </li>
      </ul>
    </div>
    <div class="inner_seller_product_content">
      <div class="steps">
        <ul>
         <li class="steps_item"><a href="#">1</a></li>
         <li><img src="<?=base_url()?>assets/images/img_dotted.png" alt="trail"></li>
         <li class="steps_item"><a href="#">2</a></li>
         <li><img src="<?=base_url()?>assets/images/img_dotted.png" alt="trail"></li>
         <li class="steps_item steps_acitve"><a href="#" class=""><span>step</span><br />3</a></li>
         <li><img src="<?=base_url()?>assets/images/img_dotted.png" alt="trail"></li>
       </ul>
     </div>

    <!-- Content -->

    <div>
      
      <table>
        <tr>
          <td>
            Shipping Service
          </td>
          <td>
            <select name="shipService1" id="shipService1"><option selected="" value="0">-</option>
              <optgroup label="Economy services">
              <option value="14">    Economy Shipping</option>
              <option value="8">    USPS Parcel Select</option>
              <option value="32">    USPS Standard Post</option>
              <option value="9">    USPS Media Mail</option>
              <option value="63">    FedEx SmartPost</option>
              </optgroup> 
              <optgroup label="Standard services">
              <option value="1">    Standard Shipping</option>
              <option value="3">    UPS Ground</option>
              <option value="10">    USPS First Class Package</option>
              <option value="62">    FedEx Ground or FedEx Home Delivery</option>
              </optgroup>
              <optgroup label="Expedited services">
              <option value="2">    Expedited Shipping</option>
              <option value="7">    USPS Priority Mail</option>
              <option value="19">    USPS Priority Mail Flat Rate Envelope</option>
              <option value="23">    USPS Priority Mail Small Flat Rate Box</option>
              <option value="20">    USPS Priority Mail Medium Flat Rate Box</option>
              <option value="22">    USPS Priority Mail Large Flat Rate Box</option>
              <option value="24">    USPS Priority Mail Padded Flat Rate Envelope</option>
              <option value="25">    USPS Priority Mail Legal Flat Rate Envelope</option>
              <option value="4">    UPS 3 Day Select</option>
              <option value="5">    UPS 2nd Day Air</option>
              <option value="64">    FedEx Express Saver</option>
              <option value="65">    FedEx 2Day</option>
              </optgroup>
              <optgroup label="One-day services">
              <option value="18">    One-day Shipping</option>
              <option value="11">    USPS Priority Mail Express</option>
              <option value="21">    USPS Priority Mail Express Flat Rate Envelope</option>
              <option value="26">    USPS Priority Mail Express Legal Flat Rate Envelope</option>
              <option value="6">    UPS Next Day Air Saver</option>
              <option value="12">    UPS Next Day Air</option>
              <option value="66">    FedEx Priority Overnight</option>
              <option value="67">    FedEx Standard Overnight</option>
              </optgroup>
              <optgroup label="Other services">
              <option value="161">    Economy Shipping from outside US</option>
              <option value="162">    Standard Shipping from outside US</option>
              <option value="163">    Expedited Shipping from outside US</option>
              <option value="167">    FedEx International Economy</option>
              <option value="150">    Local Pickup</option>
              </optgroup>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            Estimated Weight (g)
          </td>
          <td>
            <input type="text" size="28" name="weight">
          </td>
        </tr>
<!--
        <tr>
          <td>
            Dimension
          </td> 
          <td>
            <input type="text" placeholder="Length" size="5">  x
             <input type="text" placeholder="Width" size="5"> x
           <input type="text" placeholder="Height" size="5"> 
          </td>
          <tr>
             <td>
            Total
          </td>
          <td>
            <input type="text" size="28">
          </td>
          </tr> 
        </tr>
-->
      </table>

      <table>
      <tr>
        <td>Product Attribute Combinations</td>
        <td>
          <ul id="product_combination_list">
            <?php foreach($attr as $attrkey=>$temp):?>
              <li class="product_combination" value="<?php echo $attrkey;?>">
              <?php foreach($temp as $pattr):?>
                <?php echo $pattr;?> &nbsp
              <?php endforeach;?>
              </li>
            <?php endforeach;?>
          </ul>
        </td>
      </tr>
      </table>

      <table id="shiploc_selectiontbl">
      <tr>
        <td>Location</td>
        <td>Price</td>
      </tr>
      <tr>
        <td>
          <select name="shiploc1" class="shiploc">
            <option selected="" value="0">Select Location</option>
            <?php foreach($shiploc['area'] as $island=>$loc):?>
              <option value="<?php echo $shiploc['islandkey'][$island];?>"><?php echo $island;?></option>
              <?php foreach($loc as $region=>$subloc):?>
                <option value="<?php echo $shiploc['regionkey'][$region];?>"><?php echo $region;?></option>
                <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                  <option value="<?php echo $id_cityprov;?>"><?php echo $cityprov;?></option>
                <?php endforeach;?>
              <?php endforeach;?>
            <?php endforeach;?>
          </select>
        </td>
        <td>
          ₱<input type="text" name="shipprice1" class="shipprice">
        </td>
      </tr>
      <input type="hidden" value="1" id="shiploc_count" name="shiploc_count">
      <tr>
        <td>
          <a href="javascript:void(0)" id="add_location">+ Add Location</a>
        </td>
      </tr>
      </table>
      <input type="button" id="add_shipping_details" value="Add to Shipping List">
    </div>   

    <div class="clear"><br></div>

    <h2>Shipping Summary</h2>
    <table id="shipping_summary" class="tablehide">
      <input type="hidden" id="summaryrowcount" value="0">
      <tr class="cloningfield">
        <td>
        </td>
        <td>
          <table class="shiplocprice_summary">
            <tbody>
            </tbody>
          </table>
        </td>
        <td style="border:none;">
          <span class="delete_row">
            <img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete Row
          </span>
        </td>
      </tr>
      <span id="btnShippingDetails" class="tablehide">Submit</span>
    </table>

    <div id="priceloc_edit" class="cloningfield">
      <span class="priceloc_edit edit_del">
        <img src="<?php echo base_url();?>assets/images/icon_edit.png"> Edit
      </span>
      <span class="priceloc_delete edit_del">
        <img src="<?php echo base_url();?>assets/images/icon_delete.png"> Delete
      </span>
      <span class="priceloc_accept accept_cancel">
        <img src="<?php echo base_url();?>assets/images/check_.png"> Delete
      </span>
      <span class="priceloc_cancel accept_cancel">
        <img src="<?php echo base_url();?>assets/images/x_icon.png"> Delete
      </span>
    </div>

  </div>
</div>

<div class="clear"></div>  

<!--<script type='text/javascript' src="<?=base_url()?>assets/JavaScript/js/json2.js" ></script> -->


<script type="text/javascript">

$(function(){
  $('#add_location').on('click',function(){
    var datacount = $('#shiploc_count').val();
    var selecttr = $('select[name="shiploc'+datacount+'"]').closest('tr');
    var selecttrnew = selecttr.clone();
    $('#shiploc_count').val(+datacount+1);
    var select = selecttrnew.find('select[name^="shiploc"]')[0];
    select.name = "shiploc"+ (+datacount+1);
    var price = selecttrnew.find('input[name^="shipprice"]')[0];
    price.name = "shipprice"+ (+datacount+1);
    selecttr.after('<tr>' + selecttrnew.html() + '</tr>');
  });

  $('#shipping_summary').on('mouseover', '.shiplocprice_summary tbody tr', function(){
    $(this).find('span.edit_del').show();
  })
  .on('mouseleave', '.shiplocprice_summary tbody tr', function(){
    $(this).find('span.edit_del').hide();
  });
});


$(function(){
  var fdata = {};
  var displaygroup = {};
  var spanerror = '<span class="error red">Unable to select same location for same attribute</span>';
  var shiplocselectiontbl = $('#shiploc_selectiontbl');
  var priceloc_edit = $('#priceloc_edit').clone();
  
  $('#add_shipping_details').on('click', function(){
    var hasActive = hasLoc = hasPrice = false;
    var noDuplicate = true;
    var shipObj = { 'attr' : {},'loc' : {},'price' : {} };   
    var i = parseInt($('#summaryrowcount').val());

    //Get Product Attribute Options
    $('.product_combination.active').each(function(){
      shipObj.attr[$(this).val()] = $.trim($(this).text());
      hasActive = true;
    });

    //Get Shipping Location Select(s) and corresponding Price
    $('.shiploc').each(function(){
      var selopt = $(this).find('option:selected');
      var price = $(this).parent('td').next('td').children('input[name^="shipprice"]');
      
      if(selopt.val() !== 0 && price.val() !== 0 && price.val() !== '' ){
        shipObj.loc[selopt.val()] = selopt.text();
        shipObj.price[selopt.val()] = price.val();
        hasLoc = hasPrice = true;
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

    //If all fields are filled up and no duplicate entry
    if(hasActive && hasLoc && hasPrice && noDuplicate){
      var row = $('table#shipping_summary tr.cloningfield').clone();
      row.removeClass('cloningfield');
      row.find('td:first').html('');
      row.find('.shiplocprice_summary tbody').html('');
      var summaryExists = addDispGroup = false;
      var groupkey = i;

      if(i !== 0 ){
        jQuery.each(displaygroup, function(k,shipObjTemp){
          if(objectCompare(shipObj.attr, shipObjTemp)){
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

      if($('#shipping_summary').hasClass('tablehide')){
        $('#shipping_summary').removeClass('tablehide');
      }

      //Display location and price
      var nesttable = row.find('.shiplocprice_summary > tbody');
      jQuery.each(shipObj.loc, function(k,v){
        nesttable.append("<tr><td>" + v + "</td><td>" + shipObj.price[k] + "</td><td>"+priceloc_edit.html()+"</td></tr>");
      });

      //Append new summary details - CREATES NEW ROW
      if(!summaryExists){
        addDispGroup = true;
        $('#summaryrowcount').val(+i+1);
        jQuery.each(shipObj.attr, function(k,v){
          row.children('td:first').append(v + "<br />");
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
          fdata[groupkey][attrk][lock] = shipObj.price[lock];
        });
      });

      if(addDispGroup)
        displaygroup[i] = shipObj.attr;

      console.log('fdata');
      console.log(fdata);
      console.log('displaygroup');
      console.log(displaygroup);
    }//close hasloc hasactive hasprice
  });//close on click of adding ship details to summary
  


  $('#shiploc_selectiontbl').on('change', '.shiploc', function(){
    var currval = $(this).find('option:selected').val();
    var thisobj = $(this);
    var thistr = $(this).closest('tr');
    var hasDuplicate = false;

    $(this).parent('td').siblings('span.error.red').remove();

    $('.shiploc').not(this).each(function(){
      var otherval = $(this).find('option:selected').val();
      if(currval === otherval){
        hasDuplicate = true;
        return;
      }
    });
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

    if(hasDuplicate){
      thistr.find('input[name^="shipprice"]').val('');
      thistr.append(spanerror);
      thisobj.val(0);
    }

  });


  $('.product_combination').on('click', function(){

    if($(this).hasClass('active')){
      $(this).removeClass('active');
    }
    else{
      $(this).addClass('active');
    }

    if($(this).hasClass('active')){
      var attrk = $(this).val();
      
      jQuery.each(fdata, function(groupkey,attrObj){
        if(attrk in attrObj){
          $('.shiploc').each(function(){
            var lock = $(this).find('option:selected').val();
            var thistr = $(this).closest('tr');
            if(lock in attrObj[attrk]){
              thistr.find('input[name^="shipprice"]').val('');
              thistr.append(spanerror);
              $(this).val(0);
            }
          });
        }
      });

    }
  });


  $('#shipping_summary').on('click', '.delete_row', function(){
    var group = $(this).closest('tr').attr('data-group');
    $(this).closest('tr').remove();
    delete fdata[group];
    delete displaygroup[group];
    if(getObjectSize(displaygroup) === 0){
      $('#shipping_summary').addClass('tablehide');
    }
  });


}); // close doc ready function

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

function getObjectSize(obj) {
    var len = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) len++;
    }
    return len;
};

</script>