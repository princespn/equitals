<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});
Route::any('login', 'LoginController@login');

Route::any('logout', 'LoginController@logout');
Route::any('get_only_user', 'LoginController@getOnlyUser');
Route::any('get-packages-front', 'userapi\PackageController@getPackageFront');
Route::any('get-images-front', 'userapi\PackageController@getImageFront');
Route::any('get-questions', 'userapi\PackageController@getQuestions');
Route::any('get-packages-front-rupee', 'userapi\PackageController@getPackageFrontRupee');
Route::any('generate-random-no', 'userapi\PackageController@generateRandomNo');
Route::any('sendOtp-update-user-profile1', 'userapi\PackageController@sendotpEditUserProfile');
Route::any('checkotp2', 'userapi\PackageController@updateUserData');
Route::any('enquiry', 'userapi\EnquiryController@EnquiryInsert');
Route::post('checkotpadminlogin', 'LoginController@checkOtpAdminLogin');
	Route::any('country-list', 'LoginController@countrylist');

// 2fa
//Route::any('2fa/validateloginotp',  'userapi\Google2FAController@postLoginValidateToken');
Route::any('2fa/validate', 'userapi\Google2FAController@postValidateToken');
// Route::any('2fa/validatelogintoken',  'userapi\Google2FAController@loginpostValidateToken');
Route::any('2fa/enable', 'userapi\Google2FAController@enableTwoFactor');
//Route::group(['namespace' => 'user'], function() {
Route::any('check_address1', 'userapi\SettingsController@checkAddresses');

Route::any('getManualPayInfo', 'userapi\DashboardController@getManualPayInfo');
Route::namespace ('userapi')->group(function () {



	

	Route::any('enquiryform', 'UserController@enquiryform');

	Route::any('get-user-id', 'UserController@getUserId');
	Route::any('check-user-addr-token', 'UserController@checkUserAddrToken');

	Route::any('register', 'UserController@register');
	Route::any('send-registration-otp', 'UserController@sendRegistrationOtp');
    Route::any('verify-registration-otp', 'UserController@verifyRegistrationOtp');
	Route::any('get-ip-addr', 'UserController@getIpAddress');
	Route::any('reset-passwordlink', 'UserController@sendResetPasswordLink');
	Route::any('reset-password', 'UserController@resetpassword');
	Route::any('homepageUserdata', 'DashboardController@homepageUserdata');
	Route::any('checkuserexist', 'UserController@checkUserExist');

	Route::any('country', 'CommonController@getCountry');
	Route::any('rank', 'CommonController@getrank');
	Route::any('getprojectsettings', 'CommonController@getProjectSettings');
	Route::any('getprojectstatistics', 'SettingsController@getProjectStatistics');

	Route::any('get-refund-data', 'MakeDepositController@getRefundData');
    Route::any('set-refund-data', 'MakeDepositController@setRefundData');

	Route::middleware('auth:api','checkIp')->group(function () {

    	Route::any('verify-user-otp', 'UserController@verifyUserOtp');

		/* user registation */


	// flight api
		Route::any('get-news', 'DashboardController@getNews');
		//Route::any('getFlightLoc', 'FlightApiController@getFlightLoc');
		Route::any('getFlightLoc', 'FlightApiController@getFlightLocSelf');
		Route::any('searchFlight', 'FlightApiController@searchFlight');
		Route::any('getFlightLogo', 'FlightApiController@getFlightLogo');

		Route::any('searchFlightOffers', 'FlightApiController@searchFlightOffers');
		Route::any('flightCheckout', 'FlightApiController@flightCheckout');
		Route::any('getTempFlightUdata', 'FlightApiController@getTempFlightUdata');
		Route::any('saveFlightTempInfo', 'FlightApiController@saveFlightTempInfo');
		Route::any('orderFlightHistory', 'FlightApiController@orderFlightHistory');
		Route::any('travelTransactionInof', 'FlightApiController@travelTransactionInof');
		Route::any('getFlightCodeName', 'FlightApiController@getFlightCodeName');
        //--- hotel api 

		Route::any('getHotelLoc', 'HotelApiController@getHotelLoc');
		Route::any('searchHotelOffers', 'HotelApiController@searchHotelOffers');
		Route::any('searchHotelByID', 'HotelApiController@searchHotelByID');
		Route::any('getTempHoteldata', 'HotelApiController@getTempHoteldata');
		Route::any('saveHotelTempInfo', 'HotelApiController@saveHotelTempInfo');
		Route::any('hotelCheckout', 'HotelApiController@hotelCheckout');
		Route::any('orderHotelHistory', 'HotelApiController@orderHotelHistory');

		//-------------End hotel api 

		Route::any('check-downline', 'UserController@checkDownline');
		Route::any('checkuserexist/crossleg', 'UserController@checkUserExistCrossLeg');
		Route::any('getAmount', 'UserController@getAmount');
		Route::any('get-supermatchingbonusdata', 'UserController@supermatchingbonusdata');
		Route::any('get-working-balance', 'DashboardController@getWorkingBalance');
		Route::any('get-fund-balance', 'DashboardController@getFundWalletBalance');
		Route::any('get-roi-balance', 'DashboardController@getRoiBalance');
		Route::any('get-requite-balance', 'DashboardController@getRequiteBalance');
		Route::any('get-working-withdrawal-deduction', 'DashboardController@getWorkingWithdrawalDeduction');
		Route::any('get-roi-withdrawal-deduction', 'DashboardController@getRoiWithdrawalDeduction');
		Route::any('get-wallet-balance', 'DashboardController@getWalletBalance');
		Route::any('get-topup-balance', 'DashboardController@getTopupBalance');
		Route::any('sendOtp-update-user-profile', 'SendotpController@sendotpEditUserProfile');
		Route::any('checkotp1', 'UserController@updateUserData');
		Route::any('update-user-deatils', 'UserController@updateUserDataDetails');
		Route::any('2fa/validateloginotp', 'Google2FAController@postLoginValidateToken');
		Route::any('2fa/validatelogintoken', 'Google2FAController@loginpostValidateToken');
		/*Get user dashboard details*/
		/* Done */
		Route::any('getIcoStatus', 'DashboardController@getIcoStatus');
		Route::any('get-user-contest-data', 'DashboardController@getUserContestData');
		Route::any('claim_prize', 'DashboardController@claimPrize');
		Route::any('get-user-dashboard', 'DashboardController@getUserDashboardDetails');
		Route::any('get-perfect-cred', 'DashboardController@getPerfectMoneyCred');

		//Route::any('getperfect-money', 'DashboardController@getNewPerfectmoney');
		Route::any('get-user-navigation', 'DashboardController@getUsernavigationDetails');
		/*PerfectMoney */
		Route::any('getperfect-money', 'PerfectMoneyController@transferPayment');
		Route::any('create_pm_transaction', 'PerfectMoneyController@create_pm_transaction');
		Route::any('update_pm_transaction', 'PerfectMoneyController@update_pm_transaction');
		/*End of the perfectmoney*/

		Route::any('get-setting-balance', 'DashboardController@getSettingBalance');
 
		/* SUpload photos */
		Route::any('upload-photos', 'UserController@uploadPhotos');
		Route::any('get-profile-img', 'UserController@getProfileImg');
		/* End Done */
		Route::any('get-reference-id', 'UserController@getReferenceId');
		Route::any('getrank', 'UserController@getrank');
		Route::any('investmentGraphReport', 'ReportsController@investmentGraphReport');
		Route::any('myTeam', 'LevelController@direct_list');
		Route::any('fund-request', 'TopupController@fundRequest');
		Route::any('fund-request-report', 'TopupController@fundRequestReport');
		
	    /*Route::any('registerandTopup', 'TopupController@registerandTopup');*/

	    Route::any('createstructure', 'TopupController@createstructure');
	    //Route::any('registerandTopup', 'TopupController@registerandTopup');

		Route::any('sendWAMessage', 'TopupController@sendWAMessage');
		/*Transfer to wallet */
		Route::any('transfer-to-wallet', 'TransferController@transferTowallet');
		Route::any('fund-to-fund-transfer', 'TransferController@FundToFundTransfer');
		

		/* User information */
		


		Route::any('checkUserCountryAvoided', 'ProfileController@checkUserCountryAvoided');
		Route::any('get-profile-info', 'ProfileController@getprofileinfo');
		Route::any('getusddata', 'ProfileController@getusddata');
		Route::any('getuserrank', 'ProfileController@getuserrank');
		Route::any('change-password', 'UserController@changePassword');
		Route::any('change-address', 'UserController@changeAddress');
		Route::post('user-update', 'UserController@updateUserData');
		/* User information */

		Route::any('get-franchise-users', 'UserController@getFranchiseUsers');

		Route::any('get-master-franchise-users', 'UserController@getMasterFranchiseUsers');


		/*My team */
		/*Level view and direct user list*/
		Route::any('direct_list', 'ReportsController@direct_list');
		Route::any('direct_list_dash', 'ReportsController@direct_list_dash');
		Route::any('level-view', 'ReportsController@displayLevelView');
		Route::any('get-level', 'LevelController@getLevels');
		Route::any('get-referral-directs', 'ReportsController@getReferralDirects');

		/*Reports*/
		/*income Reports */
		Route::any('roi-income', 'ReportsController@ROIIncomeReport');
		Route::any('residual-income', 'ReportsController@ResidualIncomeReport');
		Route::any('supper-macthing-bonus', 'ReportsController@suppermatchingbonus');
		Route::any('freedomclubbonus', 'ReportsController@freedomclubbonus');
		Route::any('withdrwal-income', 'ReportsController@WithdrawalIncomeReport');
		Route::any('fund-to-fund-transfer-report', 'ReportsController@fundtofundTransferReport');
		
		Route::any('binary-income', 'ReportsController@binaryIncomeReport');
		Route::any('matching-bonus-income', 'ReportsController@matchingBonusIncomeReport');
		Route::any('rank-matching-bonus-income', 'ReportsController@rankMatchingBonusIncomeReport');
		Route::any('direct-income', 'ReportsController@DirectIncomeReport');
		Route::any('graphReportForIncomes','ReportsController@incomeGraphReportvue');
		Route::any('rank-report-get', 'ReportsController@getallrank');
		Route::any('Structurereport', 'ReportsController@Structurereport');
		Route::any('RegistrationStructurereport', 'ReportsController@RegistrationStructurereport');
		Route::any('franchise-income', 'ReportsController@franchiseIncomeReport');
		Route::any('level-income', 'ReportsController@LevelIncomeReport');
		Route::any('leadership-income', 'ReportsController@LeadershipIncomeReport');
		Route::any('level-income-roi', 'ReportsController@LevelIncomeRoiReport');
		Route::any('upline-roi', 'ReportsController@UplineIncomeReport');
		Route::any('userWinnerList', 'AwardRewardController@useraward');
		Route::any('getearnreport', 'ReportsController@getearnreport');
		

		/*Fund transfer */
		Route::any('wallet-list', 'TransferController@walletlist');
		Route::any('fund-transfer', 'TransferController@UserToUserTransfer');
		 Route::any('purchase-to-purchase-transfer', 'TransferController@PurchaseToPurchaseTransfer');
		Route::any('working-to-purchase-self-transfer', 'TransferController@WorkingToPurchaseSelfTransfer');
		Route::any('transfer-all-id-balance', 'TransferController@AllIDBalanceTransfer');
		Route::any('add-transfer-request', 'TransferController@AddBalanceTransferRequest');
		Route::any('add-purchase-transfer-request', 'TransferController@AddPurchaseBalanceTransferRequest');
		Route::any('check-transfer-request-exists', 'TransferController@CheckTransferRequestExist');
		Route::any('check-purchase-transfer-request-exists', 'TransferController@CheckPurchaseTransferRequestExist');
		Route::any('fund-transfer-report', 'ReportsController@fundTransferReport');
		Route::any('add-fund-report', 'ReportsController@addFundReport');
		Route::any('pending-fund-report', 'ReportsController@pendingFundReport');
		Route::any('confirm-fund-report', 'ReportsController@confirmFundReport');
		Route::any('expired-fund-report', 'ReportsController@expiredFundReport');
		Route::any('perfectmoney-fund-report', 'ReportsController@perfectMoneyFundReport');


		/*Topup */
		Route::any('topup-report', 'ReportsController@getTopupReport');
		Route::any('working-to-purchase-report', 'ReportsController@WorkingToPurchaseReport');
		Route::any('downline-topup-report', 'ReportsController@getDownlineTopupReport');
		Route::any('self-topup', 'TopupController@selfTopup');
		Route::any('manual-topup', 'TopupController@manualTopup');
		Route::any('self-topup-report', 'ReportsController@selfTopupReport');
		Route::any('manual-topup-report', 'ReportsController@manualTopupReport');
		Route::any('all-id-balance-report', 'ReportsController@AllIdBalanceReport');
		Route::any('working-transfer-balance-report', 'ReportsController@WorkingBalanceTransferReceiveReport');
		Route::any('user-transfer-balance-report', 'ReportsController@UserBalanceTransferReceiveReport');
		Route::any('purchase-transfer-balance-report', 'ReportsController@PurchaseBalanceTransferReceiveReport');
		Route::any('working-receive-balance-report', 'ReportsController@WorkingBalanceTransferReceiveReport');
		Route::any('pending-transfer-balance-request', 'ReportsController@PendingTransferBalanceRequest');
		Route::any('reinvest', 'TopupController@reinvest');
		Route::any('gecurrentuser', 'TopupController@gecurrentuser');
		Route::any('get-topupby-roi', 'TopupController@getTopupByRoi');
		Route::any('get-referral-topup', 'ReportsController@getReferralTopup');
		
		
		/* Purchase Wallet Trasfer API */
		Route::any('all-id-purchase-balance-report', 'ReportsController@AllIdPurchaseBalanceReport');
		Route::any('user-purchase-receive-balance-report', 'ReportsController@UserPurchaseTransferReceiveReport');
		Route::any('pending-purchase-balance-request', 'ReportsController@PendingPurchaseTransferBalanceRequest');
		
		/*Withdrawal*/
		/*Working and Roi Withdrawal */
		Route::any('withdraw-income', 'WithdrawTransactionController@withdrawWorkingWallet');
		Route::any('withdraw-passive-income', 'WithdrawTransactionController@withdrawPassiveWallet');
		Route::any('withdraw-income-otp', 'WithdrawTransactionController@withdrawOtp');
		Route::any('working-wallet-withdraw', 'WithdrawTransactionController@workingWalletWithdraw');
		Route::any('withdraw-roi-wallet', 'WithdrawTransactionController@withdrawRoiWallet');
		Route::any('passive-income-withdraw-list', 'WithdrawTransactionController@passiveIncomeWithdrawList');
		
		Route::any('all-withdraw-pending-reports', 'ReportsController@allwithdrawPendingReports');
		Route::any('all-withdraw-confirm-reports', 'ReportsController@allwithdrawConfirmReports');
		Route::any('withdrawHistory', 'WithdrawTransactionController@showWithdrawHistory');
		
		// Coin transfer
		Route::any('coin-transfer-user', 'WithdrawTransactionController@coinTransferUser');
		Route::any('coin_transfer_report', 'WithdrawTransactionController@coinTransferReport');
		Route::any('get-coin-balance', 'WithdrawTransactionController@getCoinBalance');

		/*All TRansaction */
		Route::any('all-transaction', 'ReportsController@getAllTransaction');
		 Route::any('gettopupdata', 'TopupController@gettopupdata');
		/*  Make deposit */
		Route::any('get-packages', 'PackageController@getpackage');
		Route::any('get-packages1', 'PackageController@getpackage1');
		Route::any('get-roi_per', 'PackageController@getRoiPer');
		Route::any('getall-currency', 'CurrencyConvertorController@getAllCurrency');
		Route::any('getaddress', 'MakeDepositController@new_address');
		Route::any('getaddress1', 'MakeDepositController@new_address1');
		Route::any('fetchAddressBalance', 'MakeDepositController@fetchAddressBalance');
		Route::any('confirm-deposit', 'MakeDepositController@ConfirmDeposit');
 
		Route::any('purchase-package', 'MakeDepositController@create_transaction');
		Route::any('get-fund-invoice', 'MakeDepositController@getFundInvoiceTransaction');
		
		/* Activity Notification */
		Route::any('all-activity', 'ActivityNotificationController@AllUserActivityNotReport');
		Route::any('activity-by-user', 'ActivityNotificationController@ActivityNotReport');

		/*To do */
		Route::any('todo-add', 'TodoController@todoAdd');
		Route::any('todo-list', 'TodoController@todoList');
		Route::any('todo-delete', 'TodoController@todoDelete');


		/*Deposit Reports*/
		Route::any('pending-deposit', 'ReportsController@pendingDeposit');
		Route::any('pending-add-deposit', 'ReportsController@pendingAddDeposit');
		Route::any('expired-add-deposit', 'ReportsController@expiredsAddDeposit');
		Route::any('confirm-add-deposit', 'ReportsController@confirmAddDeposit');
		Route::any('confirmed-deposit', 'ReportsController@confirmedDeposit');
		Route::any('dex-to-purchase-transfer-report', 'ReportsController@DexToPurchaseTransferReport');

		/*Support */
		// Route::any('send-message', 'ChatController@sendMessage');
		// Route::any('fetch-message', 'ChatController@fetchMessages');
		// Route::any('count-fetch-message', 'ChatController@fetchCountMessages');
		/* Check BTC Address */
		Route::any('check_address', 'SettingsController@checkAddresses');
		/* show promotional type */
		Route::any('show/promotional/type', 'PromotionalController@showPromotionalTypes');
		/* add promotinal */
		Route::any('store/promotional', 'PromotionalController@storePromotional');
		/* promotinal report*/
		Route::any('show/promotional', 'PromotionalController@showPromotional');
		Route::any('check/promotonalexist', 'PromotionalController@checkPromotional');
		Route::any('check_90_days', 'PromotionalController@check90Days');
		/* promotional income report*/
		Route::any('show/promotional/income', 'PromotionalController@showPromotionalIncome');
		/* Tree View */
		Route::any('getlevelviewtree/productbase', 'LevelController@getLevelsViewTreeManualProductBase');

		Route::any('team-view', 'LevelController@getTeamView');

		//========chat message routes====
		Route::post('fetch_message', 'SupportController@fetchMessages');
		Route::post('fetch_message_mob', 'SupportController@fetchMessagesMob');

		Route::post('fetch_message_read', 'SupportController@fetchMessagesRead');
		Route::post('send-message', 'SupportController@sendMessage');
		Route::any('translate', 'SupportController@translate');

		Route::any('check_app_version', 'UserController@checkAppVersion');

		
         //----shopping
Route::any('get-products','ProductController@ecommerceProductList');
Route::any('voucherTransaction', 'CheckoutController@voucherTransaction');

Route::any('getRelatedProducts','ProductController@getRelatedProducts');
Route::any('get-product-details', 'ProductController@ecommerceProductDetail');
Route::any('add-to-cart', 'CartController@addEcommerceProductToCart');
Route::any('add-to-cart', 'CartController@addEcommerceProductToCart');
Route::any('get-cart-details', 'CartController@getEcommerceCartDetails');
Route::any('remove-from-cart', 'CartController@removeProductFromCart');
Route::any('checkout', 'CheckoutController@cartCheckout');
Route::any('get-cart-with-single-product', 'CheckoutController@cartWithSingleProduct');
Route::any('get-orders','CartController@orderHistory');
Route::any('order-detail', 'CheckoutController@orderDetails'); 
Route::any('get-order-details', 'CheckoutController@ecommerceOrderDetail');

Route::any('getRelatedProducts','ProductController@getRelatedProducts');
Route::any('get-product-details', 'ProductController@ecommerceProductDetail');

Route::any('get-product-categories','ProductController@ecommerceProductCategories');

//--- ICO Coin Phase 
Route::any('geticophases', 'IcoPhasesController@getIcoPhases');
Route::any('purchaseCoin', 'IcoPhasesController@purchaseCoin');
Route::any('getpurchaseCoinRep', 'IcoPhasesController@getpurchaseCoinRep');

Route::any('sendCoinBalance', 'IcoPhasesController@sendCoinBalance');
Route::any('sendCoinRep', 'IcoPhasesController@sendCoinRep');
Route::any('downline-buy-token-report', 'ReportsController@downlineBuyTokenReport');

	});
});

//---admin api routing---//
Route::prefix('admin')->namespace('adminapi')->group(function () {
	Route::any('login', 'AuthenticationController@login');
	Route::any('getprojectsettings', 'SettingsController@getProjectSettings');
	Route::any('currencysettings', 'SettingsController@currencySettings');
	Route::any('currencyadd', 'SettingsController@currencyAdd');
	Route::any('getcurrencydetails', 'SettingsController@getCurrencyDetails');
	Route::any('updatecurrency', 'SettingsController@updateCurrency');
	Route::any('send-otp-mobile', 'UserController@sendotpOnMobile');
	Route::any('member-details', 'UserController@PMMemberDetails');
	Route::any('send-otp', 'UserController@sendOtp');
	
	//Route::any('verifyOtp', 'AuthenticationController@verifyOtp');
	Route::middleware('auth:api','checkIpAdmin')->group(function () {
		Route::any('user_login', 'AuthenticationController@userLogin');
		Route::any('logout', 'AuthenticationController@logout');
		Route::any('user-details', 'UserController@userDetails');
		Route::any('findUser', 'UserController@findUser');
		Route::any('getsummarybyuserid', 'UserController@getSummaryByUserid');
		Route::any('approve-fund-request', 'ProductController@approveFundRequest');
		Route::any('reject-fund-request', 'FundRequestController@rejectFundRequest');
		Route::any('sendOtp-add-topup', 'UserController@sendTopupOtp');
		Route::any('get-admin-navigation-detail', 'ManageAdminNavigationController@getAdminNavigationDetail');
		Route::any('send-otp-withdraw-mail', 'UserController@sendOtpWithdrawMail');


		Route::any('getrankbyuserid', 'UserController@getRankByUserid');

		Route::any('Contestachievementreport', 'UserController@getContestachievementreport');

		//---- ICO work
		Route::any('geticophases', 'IcoPhasesController@getIcoPhases');
		Route::any('transferIcoCoin', 'IcoPhasesController@transferIcoCoin');
		Route::any('getIcoPhasesLive', 'IcoPhasesController@getIcoPhasesLive');
		Route::any('getIcoBuyRep', 'IcoPhasesController@getIcoBuyRep');
		Route::any('geticophasestodaysummary', 'IcoPhasesController@getTodayPhaseSummary');
		Route::any('geticophasestopup', 'IcoPhasesController@getIcoPhasesTopup');
		Route::any('updateStatusPhasesStatus', 'IcoPhasesController@updateStatusPhasesStatus');
		Route::any('getIcoStatus', 'IcoPhasesController@getIcoStatus');
		Route::any('saveIcoStatus', 'IcoPhasesController@saveIcoStatus');
		Route::any('purchaseCoinAdmin', 'IcoPhasesController@purchaseCoinAdmin');
		Route::any('getIcoAdminBuyRep', 'IcoPhasesController@getIcoAdminBuyRep');

		Route::any('getAdminSendCoinRep', 'IcoPhasesController@getAdminSendCoinRep');
		Route::any('getUserToUserSendCoinRep', 'IcoPhasesController@getUserToUserSendCoinRep');

		Route::any('get-fund-invoice', 'FundRequestController@getFundInvoiceTransaction');

		Route::any('approve-topup-request', 'ProductController@approveTopupRequest');
		Route::any('reject-topup-request', 'ProductController@rejectTopupRequest');
		// Add BTC address By Nikunj Shah
		Route::any('add-system-btc-address', 'EWalletController@addBTCAddress');
		Route::any('get-fund-request', 'FundRequestController@getFundRequest');
		Route::any('getproducts', 'SettingsController@getProductList');
		Route::post('add_member', 'SettingsController@addMember');
		Route::post('update_member', 'SettingsController@updateMember');
		//Route::any('getproducts', 'ProductController@getProducts');

		//Manage theme routes
		Route::any('show/videos', 'ThemeController@showVideos');
		Route::any('store/video', 'ThemeController@storeVideo');
		Route::any('edit/video', 'ThemeController@editVideo');
		Route::any('update/video', 'ThemeController@updateVideo');
		Route::any('delete/video', 'ThemeController@deleteVideo');
		Route::any('show/vidtypes', 'ThemeController@showVidtypes');

		Route::any('show/gallery', 'PromotionalController@getGallryReport');
		Route::any('store/gallery', 'PromotionalController@storeGallryReport');
		Route::any('edit/gallery', 'PromotionalController@editGallry');
		Route::any('update/gallery', 'PromotionalController@updateGallryReport');
		Route::any('delete/gallery', 'PromotionalController@deleteGallry');
		/* gallry image curd */
		Route::any('show/gallery/image', 'PromotionalController@getGallryImageReport');
		Route::any('store/gallery/image', 'PromotionalController@storeGallryImage');
		Route::any('delete/gallery/image', 'PromotionalController@deleteGallryImage');

		Route::any('get/active/inactive/users', 'UserController@getActiveInactiveUsers');

		Route::any('navigations', 'NavigationsController@getNavigations');
		Route::any('create/subadmin', 'AuthenticationController@createSubadmin');
		Route::any('getsubadminsdetails', 'NavigationsController@getAllSubadminDetails');
		Route::any('getsubadmins', 'NavigationsController@getSubadmins');

		Route::any('getusers', 'UserController@getUsers');
		Route::any('getnewfranchiseusers', 'UserController@getNewFranchiseUsers');
		Route::any('makeAsFranchise', 'UserController@makeAsFranchise');
		Route::any('getwithdrawal', 'TransactionController@getWithdrawals');
		Route::any('updateuser', 'UserController@updateUser');
		Route::any('updatepaymentmode', 'TransactionController@updatePaymentMode');
		Route::any('getuserlogs', 'UserController@getUserUpdatedLog');
		Route::any('getuserpassword', 'UserController@getUserPassword');
		Route::any('updateuserpassword', 'UserController@updateUserPassword');
		
		    Route::any('showDownlineUsers', 'UserController@getDownlineUsers');
		    Route::any('findDownlineUsersBusiness', 'UserController@findDownlineUsersBusiness');
		    Route::any('saveBusinessSetting', 'UserController@saveBusinessSetting');
		    Route::any('businessSetting', 'UserController@businessSetting');

		Route::any('getcountry', 'SettingsController@getCountry');

		Route::any('store/currencyrate', 'DashboardController@storeCurrencyRate');
		Route::any('getcurrencyrate', 'DashboardController@getCurrencyRate');
		Route::any('getcurrency', 'DashboardController@getCurrency');
		Route::any('getchecktopuptime', 'DashboardController@getchecktopuptime');
		Route::any('getcheckwithdrawtime', 'DashboardController@getcheckwithdrawtime');

		Route::any('store/pushnotification', 'NotificationController@storePushnotification');
		Route::any('getpushnotification', 'NotificationController@getPushNotification');
		Route::any('update/pushnotification', 'NotificationController@updatePushNotification');
		Route::any('store/activitynotification', 'NotificationController@storeActivityNotification');
		Route::any('getactivitynotification', 'NotificationController@getActivityNotification');

		Route::any('getdepositaddresses', 'TransactionController@getDepositAddresses');
		Route::any('getdepositaddrtrans', 'TransactionController@getDepositAddrTrans');
		Route::any('getperfectmoneyreport', 'TransactionController@getPerfectmoneyReport');
		Route::any('getconfirmaddrtrans', 'TransactionController@getConfirmAddrTrans');
		Route::any('getaddrtranspendings', 'TransactionController@getAddressTransactionPending');
		Route::any('getwithdrawaltype', 'TransactionController@getWithdrawalMode');
		Route::any('getconstantsettings', 'SettingsController@getConstantSettings');

		//Route::any('getenquiries', 'EnquiryController@getEnquiry');
		/*New Route shreemant*/
		Route::any('approvePMRequest', 'TransactionController@approvePMRequest');
		Route::any('rejectPMRequest', 'TransactionController@rejectPMRequest');
		Route::any('enquiries', 'EnquiryController@getEnquiry');
		Route::any('send-enquiry-count-zero', 'EnquiryController@sendEnquiryMailCountZero');
		Route::any('send-enquiry-reply', 'EnquiryController@sendEnquiryMail');
		Route::any('enquiry-reply-report', 'EnquiryController@getReplyEnquiryReport');
		Route::any('user-link-report', 'EnquiryController@getUserLinkReport');
		/*New Route shreemant*/
		// Route::any('send/enquirymail', 'EnquiryController@sendEnquiryMail');
		// Route::any('getreplyenquiryreport', 'EnquiryController@getReplyEnquiryReport');

		Route::any('sendbulkemails', 'NotificationController@sendBulkEmails');
		Route::any('getbulkemails', 'NotificationController@getBulkEmails');

		Route::any('getsubadminnavigation', 'NavigationsController@getSubadminNavigations');
		Route::any('assignrights', 'NavigationsController@assignSubadminRights');

		Route::any('getspecificuser', 'UserController@getSpecificUserData');

		/* Done */
		Route::any('checkuserexist', 'UserController@checkUserExist');
		/* Done */
		Route::any('getMetadata', 'NavigationsController@getMetadata');

		Route::any('getDashboardSummary', 'DashboardController@getDashboardSummary');
		Route::any('getSecurityDashboardSummary', 'DashboardController@getSecurityDashboardSummary');
		Route::any('getSecurityTopups', 'DashboardController@getTopupsForSecurity');
		Route::any('getSecurityWithdrawals', 'DashboardController@getWithdrawalsForSecurity');
		
		Route::any('getaccountwallet', 'DashboardController@getAccountWallet');
		Route::any('getdexaccountwallet', 'DashboardController@getDexAccountWallet');
		Route::any('getAdminLoginDetails', 'SettingsController@getAdminLoginDetails');
		Route::any('update_withdraw_setting', 'SettingsController@UpdateWithdrawSetting');
		Route::any('getGraphicalSummary', 'DashboardController@getGraphicalSummary');

		Route::any('store/topup', 'ProductController@storeTopup');
		Route::any('store/AddRank', 'ProductController@AddRank');
		Route::any('store/freetopup', 'ProductController@storeFreeTopup');
		Route::any('get-all-rank', 'ProductController@getAllRank');
		Route::any('gettopup', 'ProductController@getTopups');
		Route::any('changeuserblockstatus', 'UserController@changeUserBlockStatus');
		Route::any('getlogincountreport', 'ProductController@getLoginCountUsersDetails');
		Route::any('getdailyreport', 'ProductController@getdailyreport');
		Route::any('getusersipaddress', 'ProductController@getUsersIPAddress');
		Route::any('topuproistop', 'ProductController@topupRoiStop');
		Route::any('Structurereport', 'ProductController@Structurereport');
		Route::any('getranks', 'ProductController@getranks');
		Route::any('getqualifyranks', 'ProductController@getqualifyranks');
		Route::any('gettopup-request', 'ProductController@getTopupsRequest');
		Route::any('oldgettopup', 'ProductController@oldgetTopups');
		Route::any('gettopuplogs', 'ProductController@getTopupLogs');
		Route::any('get/unpaid/topup', 'ProductController@getUnpaidTopups');
		Route::any('update/topup/status', 'ProductController@updateTopupStatus');

		Route::any('getlevelviews', 'LevelController@getLevelsView');
		Route::any('getuserlevels', 'LevelController@getUserLevels');

		Route::any('store/funds', 'TransactionController@addFunds');
		Route::any('deduct/funds', 'TransactionController@deductFunds');
		Route::any('getfunds', 'TransactionController@getFunds');

		Route::any('getalltransactions', 'TransactionController@getAllTransaction');

		Route::any('geticophases', 'IcoPhasesController@getIcoPhases');
		Route::any('geticophasestodaysummary', 'IcoPhasesController@getTodayPhaseSummary');
		Route::any('geticophasestopup', 'IcoPhasesController@getIcoPhasesTopup');

		Route::any('getuserprofile', 'UserController@getUserProfileDetails');
		Route::any('getadminprofile', 'UserController@getAdminProfileDetails');
		Route::any('update2fastatus', 'UserController@update2faUserStatus');
		Route::any('viewprofile', 'UserController@userProfile');

		Route::any('getroipercentage', 'LendingController@getRoiPercentage');
		Route::any('store/roipercentage', 'LendingController@storeRoiPercentage');
		Route::any('update/roipercentage', 'LendingController@updateRoiPercentage');

		Route::any('getinvestment', 'LendingController@getInvestment');
		Route::any('getinvestmentdetails', 'LendingController@getInvestmentDetails');

		Route::any('getbuyopenreport', 'ExchangeController@getBuyOpenReport');
		Route::any('getsellopenreport', 'ExchangeController@getSellOpenReport');
		Route::any('getbuysellhistory', 'ExchangeController@getBuySellHistory');

		Route::any('getexchangeratereport', 'ExchangeController@getExchangeRateReport');
		Route::any('getexchangeordersummary', 'ExchangeController@getExchangeOrderSummary');

		Route::any('getwithdrwalpending', 'EWalletController@getWithdrwalPending');
		Route::any('getwithdrwalverify', 'EWalletController@getWithdrwalVerify');
		Route::any('getwithdrwalconfirmed', 'EWalletController@getWithdrwalConfirmed');
		Route::any('getwithdrwalverified', 'EWalletController@getWithdrwalVerified');
		Route::any('send/withdrwalrequest', 'EWalletController@WithdrwalRequest');
		Route::any('verify/withdrwalrequest', 'EWalletController@WithdrwalRequestVerify');
        Route::any('reject/withdrwalrequest', 'EWalletController@WithdrwalRequestReject');
        Route::any('approve/withdrawalrequest', 'EWalletController@withdrawalRequestApprove');
        Route::any('rejected_withdrawals', 'EWalletController@rejectedWithdrawalReport');
        Route::any('approveWithdraw', 'EWalletController@approveWithdraw');

        Route::any('add-mac-address', 'EWalletController@addMacAddress');
        Route::any('get-mac-address', 'EWalletController@getMacAddress');
        Route::any('change-mac-address-status', 'EWalletController@changeMacAddressStatus');
		 
		Route::any('getlevelviewtree', 'LevelController@getLevelsViewTree');

		Route::any('getdirectincome', 'EWalletController@getDirectIncome');
		Route::any('getresidualincome', 'EWalletController@getResidualIncome');
		Route::any('getsupermatchingbonus', 'EWalletController@getsupermatchingbonus');
		Route::any('getfreedomclubdata', 'EWalletController@getfreedomclubdata');
		Route::any('getdailybussinessdata', 'EWalletController@getdailybussinessdata');
		Route::any('getbinaryincome', 'EWalletController@getBinaryIncome');
		Route::any('getmatchingbonusincome', 'EWalletController@getMatchingBonusincome');
		Route::any('getachievedmatchingbonusincome', 'EWalletController@getAchievedMatchingBonusincome');
		Route::any('getlevelincome', 'EWalletController@getLevelIncome');
		Route::any('getdailybouns', 'LendingController@getDailyBonus');
		Route::any('getLeadershipIncome', 'EWalletController@getLeadershipIncome');
		Route::any('getlevelincome-roi', 'EWalletController@LevelIncomeRoiReport');
		Route::any('getuplineincome', 'EWalletController@UplineIncomeReport');
		Route::any('/report/awardwinners', 'EWalletController@useraward');
		Route::any('getfranchiseincome', 'EWalletController@getFranchiseIncome');
		Route::any('getqualifieduser', 'EWalletController@getBinaryQualifiedUsers');
		Route::any('getbalancesheetreport', 'EWalletController@getBalanceSheetReport');
		Route::any('get-working-to-purchase-report', 'EWalletController@getWorkingToPurchaseReport');

		Route::any('getinvoice', 'EWalletController@getInvoice');
		Route::any('iptrack', 'UserController@ipTrack');
		Route::any('getdashboardreport', 'DashboardController@getDashboardReport');
		//Route::any('chat_list', 'ChatController@getAdminChattingUser');
		Route::any('confirmWithdrawl', 'EWalletController@confirmWithdrawl');
		Route::any('getWithdrawalSummary', 'EWalletController@getWithdrawalSummary');

		Route::any('show/user/designation', 'UserController@getUserDesignation');
		Route::any('show/user/addresses', 'UserController@getUserAddresses');
		
		Route::any('show/block/users', 'UserController@getBlockUsers');
		Route::any('show/unblock/users', 'UserController@getUnblockUsers');
		Route::any('blockuser', 'UserController@blockUser');
		Route::any('changeWithdrawStatus', 'UserController@changeWithdrawStatus');
		Route::any('changeUserWithdrawStatus', 'UserController@changeUserWithdrawStatus');
		
		// Route::any('unblockuser', 'UserController@unblockUser');

		Route::any('verifyuser', 'UserController@verifyUser');
		Route::any('show/franchise/users', 'UserController@getFranchiseUsersReport');

		Route::any('getnews', 'NotificationController@getNews');
		Route::any('store/news', 'NotificationController@storeNews');
		Route::any('update/news', 'NotificationController@updateNews');
		Route::any('delete/news', 'NotificationController@deleteNews');
		Route::any('edit/news', 'NotificationController@editNews');

		Route::any('store/representative', 'UserController@storeRepresentative');
		Route::any('update/representative', 'UserController@updateRepresentative');
		Route::any('delete/representative', 'UserController@deleteRepresentative');
		Route::any('show/representative', 'UserController@showRepresentative');

		Route::any('videolist', 'PromotionalController@videolist');
		Route::any('approvevideo', 'PromotionalController@approvevideo');
		Route::any('rejectHidevideo', 'PromotionalController@rejectHidevideo');
		Route::any('show/promotionalincome', 'PromotionalController@getPromotionalIncome');

		Route::any('show/products', 'ProductController@getProducts');
		Route::any('store/product', 'ProductController@storeProduct');
		Route::any('edit/product', 'ProductController@editProduct');
		Route::any('update/product', 'ProductController@updateProduct');
		Route::any('delete/product', 'ProductController@deleteProduct');

		Route::any('show/auto/withdrwalpending', 'EWalletController@getAutoWithdrwalPending');
		Route::any('show/auto/withdrwalconfirmed', 'EWalletController@getAutoWithdrwalConfirmed');
		Route::any('send/auto/withdrwalrequest', 'EWalletController@autoWithdrwalRequest');

		Route::post('getpopup', 'NotificationController@getPopup');
		Route::post('store/popup', 'NotificationController@storePopup');
		Route::post('update/popup', 'NotificationController@updatePopup');
		Route::post('delete/popup', 'NotificationController@deletePopup');
		Route::post('edit/popup', 'NotificationController@editPopup');

		/* Fund routes */
		Route::post('fund_request', 'FundRequestController@fundRequest');
		Route::post('fund-wallet-action', 'FundRequestController@fund_wallet_action');
		Route::post('fund_report', 'FundRequestController@fundReport');
		Route::post('fund_transfer_report', 'FundRequestController@fundTransferReport');
		Route::post('wallet_transaction_report', 'FundRequestController@WalletTransactionReport');
		Route::post('fake_topup_report', 'FundRequestController@fakeTopupReport');
		Route::post('fake_withdraw_report', 'FundRequestController@fakeWithdrawReport');
		Route::post('dex_to_purchase_transfer_report', 'FundRequestController@DexToPurchaseTransferReport');
		Route::post('balance_transfer_report', 'FundRequestController@balanceTransferReport');
		Route::post('purchase_balance_transfer_report', 'FundRequestController@PurchaseBalanceTransferReport');
		Route::post('setting_fund_report', 'FundRequestController@SettingfundReport');
		/* Fund Deduction routes */
		Route::post('fund_deduction', 'FundRequestController@amountDeduction');
		Route::post('deduction_report', 'FundRequestController@deductionReport');

		//========chat message routes====
		Route::post('fetch_message', 'SupportController@fetchMessages');
		Route::post('send-message', 'SupportController@sendMessage');
		Route::any('chat_list', 'SupportController@getAdminChattingUser');
		Route::any('alluser_list', 'SupportController@getAllUserList');
		Route::any('translate', 'SupportController@translate');
		Route::any('support-count', 'SupportController@supportCount');

		// Route::post('fetch_message', 'ChatController@fetchMessages');
		//========chatt send message====
		Route::post('send_message', 'ChatController@sendMessage');

		/* show promotional type */
		Route::any('show/promotional/type', 'PromotionalController@showPromotionalTypes');
		/* promotinal report*/
		Route::any('show/promotional', 'PromotionalController@showPromotional');
		/* promotinal report */
		Route::any('approve/reject/promotional', 'PromotionalController@approveRejectPromotional');
		/* promotional income report*/
		Route::any('show/promotional/income', 'PromotionalController@showPromotionalIncome');
		/* Tree View*/
		Route::any('getlevelviewtree/productbase', 'LevelController@getLevelsViewTreeManualProductBase');

		Route::any('getteamviews', 'LevelController@getTeamViews');

		Route::any('add-power', 'UserController@addPower');
		Route::any('power-report', 'UserController@powerReport');
		Route::any('get-franchise-users', 'UserController@getFranchiseUsers');
		Route::any('get-master-franchise-users', 'UserController@getMasterFranchiseUsers');
		Route::any('get-changeidreport', 'UserController@useridUpdate');
		Route::any('updateuserid', 'UserController@updateUserid');

		Route::any('add-bussiness', 'UserController@addBussiness');
		Route::any('add-bussiness-bv', 'UserController@addBussinessbv');
		Route::any('bussiness-report', 'UserController@buinessReport');
		Route::any('bussiness-upline-report', 'UserController@buinessUplineReport');
		Route::any('upline-bussiness-report', 'UserController@uplineBusinessReport');
		Route::any('remove-power-bv', 'UserController@removepowerbv');

		Route::any('remove_fund_request', 'FundRequestController@removefundRequest');
		Route::post('remove_fund_report', 'FundRequestController@removefundReport');
		Route::post('setting_remove_fund_report', 'FundRequestController@settingremovefundReport');

		Route::post('remove_dxwallet_fund', 'FundRequestController@remove_dxwallet_fund');
		Route::post('remove_dxwallet_fundReport', 'FundRequestController@remove_dxwallet_fundReport');
		Route::any('add_rankpower', 'UserController@AddRankPower');
		Route::any('rank_power_report', 'ProductController@RankPowerReport');
		Route::any('add_rankpower_upline', 'UserController@AddRankPowerUpline');
		Route::any('add-bussiness-upline', 'UserController@addBussinessUpline');
		Route::any('rank_power_upline_report', 'ProductController@RankPowerUplineReport');
		Route::any('rank_bussiness_upline_report', 'ProductController@BussinessUplineReport');
		Route::any('getRankCount', 'UserController@getRankCount');
		Route::any('updatebulkusers', 'UserController@updateBulkUsers');
		Route::any('bulkedit-report', 'UserController@BulkEditReport');



		// Category 
		Route::any('store/product-category', 'EcommerceProductController@storeEcommerceProductCategory');
        Route::any('update/product-category', 'EcommerceProductController@updateEcommerceProductCategory');
        Route::any('delete/product-category', 'EcommerceProductController@deleteEcommerceProductCategory');
        Route::any('get/product-category', 'EcommerceProductController@getEcommerceProductCategories');
        Route::any('edit/product-category', 'EcommerceProductController@editEcommerceProductCategory');
        Route::any('add/multiple-img', 'EcommerceProductController@addProductImages');

        Route::any('store/ecommerce-product', 'EcommerceProductController@storeEcommerceProduct');
        Route::any('update/ecommerce-product', 'EcommerceProductController@updateEcommerceProduct');
        Route::any('delete/ecommerce-product', 'EcommerceProductController@deleteEcommerceProduct');
        Route::any('getEcommerceProductsImg', 'EcommerceProductController@getEcommerceProductsImg');
        Route::any('edit/ecommerce-product', 'EcommerceProductController@editEcommerceProducts');
        Route::any('get_countries', 'EcommerceProductController@getCountries');
        // Route::any('getecommerceproducts', 'EcommerceProductController@getEcommerceProducts'); // old main product list in admin with all sub variants list
        Route::any('getecommerceproducts', 'EcommerceProductController@getMainEcommerceProducts'); // New
        Route::any('product-variation-list', 'EcommerceProductController@productVariationList');
        Route::any('delete/product-variation', 'EcommerceProductController@deleteProductVariation');

        Route::any('productImgStatus', 'EcommerceProductController@productImgStatus');

        Route::any('user-orders', 'UserOrderController@getUserOrder');
        Route::any('approveOrderRequest', 'UserOrderController@approveOrderRequest');
        Route::any('reject-order-request', 'UserOrderController@rejectOrderRequest');
        Route::any('view-order-detail', 'UserOrderController@getUserOrderDetail');
        Route::any('saveCoupon', 'UserOrderController@saveCoupon');

		//flight booking
		Route::any('get-flight-booking', 'FlightApiController@getFlightOrder');
        Route::any('approveBookingRequest', 'FlightApiController@approveBookingRequest');
        Route::any('reject-booking-request', 'FlightApiController@rejectBookingRequest');
        Route::any('view-booking-details', 'FlightApiController@viewBookingDetails');
        Route::any('getFlightList', 'FlightApiController@getFlightList');
        Route::any('uploadFlightLogo', 'FlightApiController@uploadFlightLogo');
        Route::any('addFlightName', 'FlightApiController@addFlightName');

		//Hotel booking
		Route::any('get-hotel-booking', 'FlightApiController@getHotelOrder');
        Route::any('view-hotelbooking-details', 'FlightApiController@viewHotelBookingDetails');
        Route::any('approvehotelBookingRequest', 'FlightApiController@approveHotelBookingRequest');
        Route::any('reject-hotelbooking-request', 'FlightApiController@rejectHotelBookingRequest');

        Route::post('add_setting_fund', 'FundRequestController@addSettingFund');
		Route::post('add_setting_fund_report', 'FundRequestController@addSettingFundReport');
	});
});
