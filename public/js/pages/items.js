//#############################################################
//#############################################################
//##
//##    Convenience function to strip known html entities.
//##
//#############################################################
//#############################################################
let items_entityMap = {
  "\\+": ' ',
  "\\&#039;": '\'',
  "\\&#x00027;": '\'',
  "\\&apos;": '\''
};

let items_keys = Object.keys(items_entityMap);

//#############################################################
//#############################################################
//##
//##    LAME function to remove/replace specials characters.
//##
//#############################################################
//#############################################################
var stripit = function (__val) {
  for (let i = 0; i < items_keys.length; i++) {
    try {
      __val = decodeURIComponent(__val).replace(new RegExp(items_keys[i], "g"), items_entityMap[items_keys[i]]);
    } catch (e) {
      console.log(JSON.stringify(e));
    }
  }
  return __val;
};

//#############################################################
//#############################################################
//##
//##    Convenience function to get a human readable date string.
//##
//#############################################################
//#############################################################
var getPrettyTime = function (date) {
  return (date ? date : new Date()).format('Il F jS, Y g:i A').substring(1);
};

var JQUTILS = {
  getVal: function (id) {
    var hasHash = id && id.indexOf('#') !== -1;
    var _id = !hasHash ? `#${id}` : id;
    return $(_id).val();
  },
  clearVal: function (id) {
    return $(id).val('');
  }
};

//#############################################################
//#############################################################
//##
//##    Utility method that wraps up and delivers override 
//##    price modal markup.
//##
//#############################################################
//#############################################################
var wrapModal = function (row) {
  var wrapper = ' <div id="detailRowModal" class="modal fade dtr-bs-modal in" role="dialog" style="display: block; padding-left: 0px;">\n\
                            <div class="modal-dialog" role="document">\n\
                                <div class="modal-content">\n\
                                    <div class="modal-header">\n\
                                        <h4 class="modal-title">\n\
                                            ' + generateDetailsModalHeader(row) + '\n\
                                        </h4>\n\
                                    </div>\n\
                                <div class="modal-body">\n\
                                ' + generateDetailsModalBody(row.index(), row.data()) + '\n\
                                </div>\n\
                            </div>\n\
                        </div>';
  return wrapper;
};

//#############################################################
//#############################################################
//##
//##    Utility method that generates raw markup for override 
//##    price popup modal header.
//##
//#############################################################
//#############################################################
var generateDetailsModalHeader = function (row) {
  var data = row.data();
  return data['productname'] + ' <div class="pull-right"><small ><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> <span >' + data['sku'] + '</span></small><span><button type="button" class="close" data-dismiss="modal">&times;</button></span></div>';
};

//#############################################################
//#############################################################
//##
//##    Utility method to generate raw html for modal popup
//##    override price form.
//##
//#############################################################
//#############################################################
var generateDetailsModalBody = function (rowIdx, columns) {
  var comment = columns.comment ? columns.comment : '';
  var option = columns.option;
  var qty = columns.qty;
  var wholesale = columns.wholesale;
  var retail = columns.retail;
  var overrideprice = columns.overrideprice ? columns.overrideprice : '';
  var uom = columns.uom;
  var id = columns.id;
  var status = columns.status;
  var saturdayenabled = columns.saturdayenabled;
  var data = '';
  var copy = '<div class="row">\n\
                                        <div class="col-md-12">\n\
                                            <hr/>\n\
                                        </div>\n\
                                    </div>';
  if ((comment && comment.length) ||
          (option && option.length)) {
    copy += '   <div class="row">\n\
                                        <div class="col-md-12">\n\
                                            <div class="table-responsive">\n\
                                                <table class="table">\n\
                                                    <thead>\n\
                                                        <tr>\n\
                                                            <th>Comment</th>\n\
                                                            <th>Option</th>\n\
                                                        </tr>\n\
                                                    </thead>\n\
                                                    <tbody>\n\
                                                        <tr>\n\
                                                            <td style="width: 50%;">' + comment + '</td>\n\
                                                            <td style="width: 50%;">' + option + '</td>\n\
                                                        <tr>\n\
                                                    </tbody>\n\
                                                </table>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>';
  }
  copy += '   <div class="row">\n\
                                        <div class="col-md-12">\n\
                                            <div class="table-responsive">\n\
                                                <table class="table">\n\
                                                    <thead>\n\
                                                        <tr>\n\
                                                            <th>Wholesale</th>\n\
                                                            <th>Retail</th>\n\
                                                            <th>Override Price</th>\n\
                                                        </tr>\n\
                                                    </thead>\n\
                                                    <tbody>\n\
                                                        <tr>\n\
                                                            <td style="width: 33%;">' + wholesale + '</td>\n\
                                                            <td style="width: 33%;">' + retail + '</td>\n\
                                                            <td style="width: 33%;">' + overrideprice + '</td>\n\
                                                        <tr>\n\
                                                    </tbody>\n\
                                                </table>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>';

  copy += '   <div class="row">\n\
                                        <div class="col-md-12">\n\
                                            <div class="table-responsive">\n\
                                                <table class="table">\n\
                                                    <thead>\n\
                                                        <tr>\n\
                                                            <th>Qty</th>\n\
                                                            <th>UOM</th>\n\
                                                            <th>Saturday</th>\n\
                                                        </tr>\n\
                                                    </thead>\n\
                                                    <tbody>\n\
                                                        <tr>\n\
                                                            <td style="width: 33%;">' + qty + '</td>\n\
                                                            <td style="width: 33%;">' + uom + '</td>\n\
                                                            <td style="width: 33%;">' + saturdayenabled + '</td>\n\
                                                        <tr>\n\
                                                    </tbody>\n\
                                                </table>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>';
  //#############################################################
  //#############################################################
  //##
  //##    IF row.status = Disabled
  //##    Allow Price Override, but warn the Salesperson.
  //##
  //#############################################################
  //#############################################################
  if (status && status === 'Disabled') {
    data += '<div class="row">';
    data += '       <div class="col-md-12">';
    data += '           <div class="ffm-callout ffm-callout-error">\n\
                                                    <h4 >Notice <small class="pull-right">You must call first.</small></h4>\n\
                                                    <hr/>\n\
                                                    <p>\n\
                                                        This item is marked Out Of Stock. Please call for availability.\n\
                                                    </p>\n\
                                                </div>';
    data += '       </div>';
    data += '</div>';
  }
  data += '   <div class="row">';
  data += '       <div class="col-md-12">';
  data += '           <form id="override_form" class="form" novalidate="novalidate">';
  data += '               <div class="row form-group">\n\
                                                    <div class="col-md-4">\n\
                                                        <label for="overrideprice">\n\
                                                            <i>Override Price</i>&nbsp;<span style="color:red;">*</span> \n\
                                                        </label>\n\
                                                    </div>\n\
                                                    <div class="col-md-4">\n\
                                                        <div class="input-group">\n\
                                                            <div class="input-group-addon">$</div>\n\
                                                            <input type="text" class="form-control" value="' + overrideprice + '" required data-msg="Override Price is required" id="overrideprice" aria-describedby="priceHelpInline" placeholder="Price">\n\
                                                        </div>\n\
                                                    </div>\n\
                                                    \n\
                                                    <div class="col-md-4">\n\
                                                        <small id="priceHelpInline" class="text-muted"> -- Enter Price Override.</small>\n\
                                                    </div>\n\
                                                </div>';
  data += '               <div class="row form-group">';
  data += '                       <div class="col-md-4 col-md-offset-4">\n\
                                                            <span class="pull-right">';
  //#############################################################
  //#############################################################
  //##
  //##    Override Price Click Handler.
  //##
  //#############################################################
  //#############################################################
  data += '                                       <button data-toggle="tooltip" data-dismiss="modal" title="Press To Cancel" class="btn btn-danger" >\n\
                                                                    Close\n\
                                                                </button>\n\
                                                            </span>\n\
                                                        </div>\n\
                                                        <div class="col-md-4">\n\
                                                            <span class="pull-left">\n\
                                                                <button id="override_form_submit" onclick="initForm()" type="submit" data-ffm-row-index="' + rowIdx + '" data-ffm-id="' + id + '" \n\
                                                                        data-toggle="tooltip" title="Submit Price Override" class="btn btn-default">\n\
                                                                    Override\n\
                                                                </button>\n\
                                                            </span>\n\
                                                        </div>\n\
                                                </div>';
  data += '           </form>';
  data += '       </div>';
  data += '   </div>';
  return data + copy;
};



