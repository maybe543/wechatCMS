// YWA instrumentation on GOMAJI TW
// Created by: Tsung-Yu Lee 
// Date: 2013/12/12
// Last edit by: Tsung-Yu
// Date: 2013/12/31
// Note: Filtered doStartBuy myDB undefined case
//       Added subjects pages for deal-list.php, store-detail.php
//       Added product pages of index-sub.php, deal.php, travel-sub.php
//       Modified Tag_List undefined case and added yellowpage.php
//       Fixed bug: Cat X Sub_Cat, store-detail
//       Added function for getting GOMAJI app page

var proj = {pid: "1000473865585",
            region: "APAC",
            name: "GOMAJI TW"};

var beacons = {PC: "1197726972",
               Mobile: "1197726973",
               Local: "1197726974",                  
               Deliver : "1197726975",
               Travel: "1197726976",
               Ticket: "1197726977",
               DownloadApp: "1197726978",
               GetApp: "1197726979",
               Add_To_Cart: "1197726980",
               SubscribePage: "1197726981",
               Subscribe: "1197726982",
               LinkDownload: "1197726983",
               AppDownload: "1197726984",
               Billing_Conf: "1197726985",
               AppStore: "1197727023"};
var CF = {Platform: 7,  
          Subject: 8,
          Region: 9,
          City: 10,
          Cat: 11,
          Sub_Cat: 12,
          Promo: 13,
          Prod_Name: 14,
          SKU: 17,
          Tag_List: 18};
var actions = {SubscribePage: "20",
               Subscribe: "21",
               LinkDownload: "22",
               AppDownload: "23",
               BillingConf: "01",
               AppStore: "24"};

if(location.protocol == "https:")
	document.write("<script type='text/javascript' src='../https@s.yimg.com/mi/apac/ywa.js'></script>");
else
	document.write("<script type='text/javascript' src='../d.yimg.com/mi/apac/ywa.js'></script>");
if(typeof jQuery == "undefined")
  document.write("<script type='text/javascript' src='../ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js'></script>");

var myDB = {
  region: {"1": "北部地区", "2": "中部地区", "3": "南部地区", "4": "花东地区"},
  category_id: {"1": "美食", "4": "美类", "10": "生活", "12": "美食飨宴", "13": "精品生活", "14": "3C家电",
                "15": "流行时尚", "16": "美妆保养", "17": "妈咪宝贝", "26": "展览", "28": "游乐", "29": "亲子", "30": "家具家饰"},
  tag_id: {"2": "中式料理", "6": "西式料理", "5": "日式料理", "4": "美式餐厅", "9": "义式料理", "7": "异国料理", "15": "吃到饱",
           "8": "咖啡轻食", "10": "甜点冰品", "33": "美容SPA", "32": "美发", "37,39": "美甲美睫", "now": "即日起可用/即买即用"},
  sort: {"latest": "最新", "popularity": "热门", "amount": "销量"},
  city: {"Taiwan": "台湾", "Taipei": "台北", "Taichung": "台中", "Kaohsiung": "高雄", "Taoyuan": "桃园", "Tainan": "台南",
         "Hsinchu": "新竹", "Yilan": "宜兰", "Keelung": "基隆", "Chiayi": "嘉义", "Pingtung": "屏东", "Changhua": "彰化",
         "Miaoli": "苗栗", "Nantou": "南投", "Hualien": "花莲", "Yunlin": "云林", "Taitung": "台东"}
}

/******                Functions                 ******/

function getTracker() {
  try {
    return YWA.getTracker(proj.pid, proj.region);
  } catch (eYWATCUnavailable) {
    if (window.console && window.console.warn) {
      window.console.warn(eYWATCUnavailable.message || "Unknown error");
    return;
    }
  }
}

function doBeacon(beacon) {
   var YHB=new Image();
   YHB.src="../pclick.yahoo.com/p/s="+beacon+"&t="+Math.random();
}

function doYCP(pixType, cat, productId) {
  var typeToID = {"ProdPage": "2rl8c7sl"};
  var YCP = new Image(),
      base_url = "../pm.ap.dp.yieldmanager.net/PixelMonkey@pixelId="+ typeToID[pixType] +"&format=image&useReferrer=1&additionalParams=";
  if(location.protocol == "https:")
    base_url="../https@s-pm-ap.dp.yieldmanager.net/PixelMonkey@pixelId="+ typeToID[pixType] +"&format=image&useReferrer=1&additionalParams=";  
  YCP.src = base_url + cat + "pid:" + productId;
}

// function doYCPConversion(){
//   var YCP = new Image();
//   YCP.src = "../https@s-pm-ap.dp.yieldmanager.net/ConversionMonkey@adId=ycp_apac_4kzc2eok";
// }

function getURLVars(url) {
	var vars = {},
		hash;
	var hashes = url.slice(url.indexOf("?") + 1).split("&");
	for(var i=0; i<hashes.length; i++){
		hash = hashes[i].split("=");
		vars[hash[0]] = hash[1];
	}
	return vars;
}

function doYWA() {
   	var YWATracker = getTracker();
   	var url_tail = (document.URL.split(".com/")[1] === undefined)? document.URL : document.URL.split(".com/")[1];
    var url_vars = getURLVars(url_tail);

    var platform_str = "";
    if(document.domain === "127.0.0.1" || document.domain === "127.0.0.1"){
      platform_str = "Mobile";
    }else if(document.domain === "127.0.0.1" || document.domain === "127.0.0.1"){
      platform_str = "PC";
    }

    var subject_str = "其他";
    var city_str = "";
    // 本地团购也有可能以地名的形式出现在url尾端. ex: www.gomaji.com/Taoyuan
    // 宅配购: www.gomaji.com/Taiwan
    if(url_tail.indexOf(".php") === -1 && url_tail.indexOf(".html") === -1 && url_tail.indexOf("instantdeal") === -1 && document.URL !== "./"){
      if(url_tail === "Taiwan"){
        subject_str = "宅配购";
        city_str = "台湾";
      }else{
        subject_str = "本地团购";
        if(!url_vars.city){
          url_tail = url_tail.split("#")[0];
          city_str = (myDB["city"][url_tail] !== undefined)? myDB["city"][url_tail] : "Others";
        }
      }
    }else{
      if(url_tail.indexOf("index.php") !== -1){
        if(url_tail.indexOf("Taiwan") !== -1){
          subject_str = "宅配购";
          city_str = "台湾"
        }else{
          subject_str = "本地团购";
          // if(url_vars.city){
          //   city_str = (myDB["city"][url_vars.city] !== undefined)? myDB["city"][url_vars.city] : "Others";
          // }
        }
      }else if(url_tail.indexOf("travel.php") !== -1){
        subject_str = "一起旅行";
      }else if(url_tail.indexOf("ticket.php") !== -1){
        subject_str = "票！";
      }else if(url_tail.indexOf("event/201112/instantdeal") !== -1) {
        subject_str = "下载APP好康带着走";
      }else if(url_tail.indexOf("deal-list.php") !== -1){
        subject_str = "过往团购";
      }else if(url_tail.indexOf("store-detail.php") !== -1 || url_tail.indexOf("yellowpage.php") !== -1){
        subject_str = "精选店家";
      }
    }
    if(url_vars.city){
      city_str = (myDB["city"][url_vars.city] !== undefined)? myDB["city"][url_vars.city] : "Others";
    }

    var region_str = "";
    if(url_vars.region){
      switch(url_vars.region){
        case "1":
          region_str = "北部地区";
          break;
        case "2":
          region_str = "中部地区";
          break;
        case "3":
          region_str = "南部地区";
          break;
        case "4":
          region_str = "花东地区";
          break;
        default:
          region_str = url_vars.region;
          break;
      }
    }

    var cat_str = "";
    // 即日起可用/即买即用 以tag_id分
    if(url_vars.category_id){
      switch(url_vars.category_id){
        case "1":
          cat_str = "美食";
          break;
        case "4":
          cat_str = "美类";
          break;
        case "10":
          cat_str = "生活";
          break;
        case "12":
          cat_str = "美食飨宴";
          break;
        case "13":
          cat_str = "精品生活";
          break;
        case "14":
          cat_str = "3C家电";
          break;
        case "15":
          cat_str = "流行时尚";
          break;
        case "16":
          cat_str = "美妆保养";
          break;
        case "17":
          cat_str = "妈咪宝贝";
          break;
        case "26":
          cat_str = "展览";
          break;
        case "28":
          cat_str = "游乐";
          break;
        case "29":
          cat_str = "亲子";
          break;
        case "30":
          cat_str = "家具家饰";
          break;
        default:
          cat_str = url_vars.category_id;
          break;
      }
    }

    var subcat_str = "";
    if(url_vars.tag_id){
      switch(url_vars.tag_id){
        case "2":
          cat_str = "美食";
          subcat_str = "中式料理";
          break;
        case "6":
          cat_str = "美食";
          subcat_str = "西式料理";
          break;
        case "5":
          cat_str = "美食";
          subcat_str = "日式料理";
          break;
        case "4":
          cat_str = "美食";
          subcat_str = "美式餐厅";
          break;
        case "9":
          cat_str = "美食";
          subcat_str = "义式料理";
          break;
        case "7":
          cat_str = "美食";
          subcat_str = "异国料理";
          break;
        case "15":
          cat_str = "美食";
          subcat_str = "吃到饱";
          break;
        case "8":
          cat_str = "美食";
          subcat_str = "咖啡轻食";
          break;
        case "10":
          cat_str = "美食";
          subcat_str = "甜点冰品";
          break;
        case "33":
          cat_str = "美类";
          subcat_str = "美容SPA";
          break;
        case "32":
          cat_str = "美类";
          subcat_str = "美发";
          break;
        case "37,39":
          cat_str = "美类";
          subcat_str = "美甲美睫";
          break;
        case "now":
          cat_str = "即日起可用/即买即用";
          break;
        default:
          subcat_str = url_vars.tag_id;
          break;
      }
    }

    var promo_str = "";
    if(url_vars.sort){
      switch(url_vars.sort){
        case "latest":
          promo_str = "最新";
          break;
        case "popularity":
          promo_str = "热门";
          break;
        case "amount":
          promo_str = "销量";
          break;
        default:
          promo_str = url_vars.sort;
          break;
      }
    }

    // Product Name & SKU
    var sku = "";
    if(url_tail.indexOf(".html") !== -1){
      sku = (url_tail.split("_")[1] === undefined)? url_tail.split("p")[1].split(".html")[0] : url_tail.split("_")[1].split("p")[1].split(".html")[0];
    }else if(url_tail.indexOf("index-sub.php") !== -1 || url_tail.indexOf("deal.php") !== -1 || url_tail.indexOf("travel-sub.php") !== -1){
      if(url_vars.pid)
        sku = url_vars.pid;
    }
    if(/\d+/.test(sku)){
        // YWATracker.setCF(CF.Prod_Name, $('span.product-name').text());
      YWATracker.setCF(CF.SKU, sku);
      YWATracker.setSKU(sku);
      var tags = getURLVars(document.referrer);
      var tagList = "";
      var ycp_cats = "";
      for(key in tags) {
        if(tags[key] !== undefined){
          if(myDB[key] !== undefined){
            if(myDB[key][tags[key]] !== undefined)
              tagList += myDB[key][tags[key]] + "$";
            else
              tagList += key + ":" + tags[key] + "$";
          }
          if(key === "store_id")
            tagList += key + ":" + tags[key] + "$";
          ycp_cats += key + ":" + tags[key] + ";";            
        }
      }
      YWATracker.setCF(CF.Tag_List, tagList.substr(0, tagList.length - 1));
      YWATracker.setAction("PRODUCT_VIEW");
      doYCP("ProdPage", ycp_cats, sku);
      if($('span.product-name').text()){
        YWATracker.setCF(CF.Prod_Name, $('span.product-name').text());
      }
      subject_str = "商品页";
    }

    YWATracker.setCF(CF.Platform, platform_str);
    YWATracker.setCF(CF.Subject, subject_str);
    YWATracker.setCF(CF.Region, region_str);
    YWATracker.setCF(CF.City, city_str);
    YWATracker.setCF(CF.Cat, cat_str);
    YWATracker.setCF(CF.Sub_Cat, subcat_str);
    YWATracker.setCF(CF.Promo, promo_str);

    YWATracker.submit();

    if(platform_str === "PC"){
      doBeacon(beacons.PC);
    }else if(platform_str === "Mobile"){
      doBeacon(beacons.Mobile);
    }

    if(subject_str !== "其他"){
      switch(subject_str){
        case "本地团购":
          doBeacon(beacons.Local);
          break;
        case "宅配购":
          doBeacon(beacons.Deliver);
          break;
        case "一起旅行":
          doBeacon(beacons.Travel);
          break;
        case "票！":
          doBeacon(beacons.Ticket);
          break;
        case "下载APP好康带着走":
          doBeacon(beacons.DownloadApp);
          break;
      }
    }
}

function doStartBuy(productId) {
    var YWATracker = getTracker();
    YWATracker.setAction("ADD_TO_CART");
   	YWATracker.setSKU(productId);
    YWATracker.setCF(CF.SKU, productId);
    var tags = getURLVars(document.referrer);
    var tagList = "";
    for(key in tags) {
      if(tags[key] !== undefined){
        if(myDB[key] !== undefined)
          tagList += myDB[key][tags[key]] + "$";
        else
          tagList += key + ":" + tags[key] + "$";
      }
    }
    YWATracker.setCF(CF.Tag_List, tagList.substr(0, tagList.length - 1));
   	YWATracker.submit_action();
   	doBeacon(beacons.Add_To_Cart);
}

function doSubscribe(type) {
  var YWATracker = getTracker();
  switch(type){
    case "page":
      YWATracker.setAction(actions.SubscribePage);
      doBeacon(beacons.SubscribePage);
      break;
    case "confirm":
      YWATracker.setAction(actions.Subscribe);
      doBeacon(beacons.Subscribe);
      break;
  }
  YWATracker.submit_action();
}

function doDownload(type) {
  var YWATracker = getTracker();
  switch(type){
    case "PC":
      YWATracker.setAction(actions.LinkDownload);
      doBeacon(beacons.LinkDownload);
      break;
    case "MobileInstall":
      YWATracker.setAction(actions.AppDownload);
      doBeacon(beacons.AppDownload);
      break;
    case "MobileAppStore":
      YWATracker.setAction(actions.AppStore);
      doBeacon(beacons.AppStore);
      break;
  }
  YWATracker.submit_action();
}

function doBillingConfirm(orderId, productList, units, amounts, totalAmount) {
  var YWATracker = getTracker();
	YWATracker.setAction(actions.BillingConf);
	if(orderId)
		YWATracker.setOrderId(orderId);
	if(productList){
		YWATracker.setSKU(productList);
    YWATracker.setCF(CF.SKU, productList);
  }
	if(units)
		YWATracker.setUnits(units);
	if(amounts){
    YWATracker.setAmounts(amounts);
  }
	if(totalAmount) {
		totalAmount = "TWD" + totalAmount;
		YWATracker.setAmount(totalAmount);
	}
	YWATracker.submit_action();
	doBeacon(beacons.Billing_Conf);
  // doYCPConversion();
}

function doAppPage(){
  var YWATracker = getTracker();
  YWATracker.setCF(CF.Platform, "Mobile");
  YWATracker.setCF(CF.Subject, "取得GOMAJI应用程序");
  YWATracker.submit();
  doBeacon(beacons.GetApp);
}
