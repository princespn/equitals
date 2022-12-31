import VueRouter from "vue-router";

import LoginComponent from "./../components/admin/LoginComponent.vue";
import DashboardComponent from "./../components/admin/DashboardComponent.vue";
import UserProfileComponent from "./../components/admin/UserProfileComponent.vue";
import ProductReportComponent from "./../components/admin/ProductReportComponent.vue";
import ManageUserComponent from "./../components/admin/ManageUserComponent.vue";
import ManageUserFranchiseComponent from "./../components/admin/ManageUserFranchiseComponent.vue";
import ChangeUserPasswordComponent from "./../components/admin/ChangeUserPasswordComponent.vue";
import EditUserProfileComponent from "./../components/admin/EditUserProfileComponent.vue";
import EditWithdrawalModeComponent from "./../components/admin/EditWithdrawalModeComponent.vue";
import EditUserProfileReportComponent from "./../components/admin/EditUserProfileReportComponent.vue";
import BlockUsersReportComponent from "./../components/admin/BlockUsersReportComponent.vue";
import UserBTCAddressReportComponent from "./../components/admin/UserBTCAddressReportComponent.vue";
import LevelIncomeReportComponent from "./../components/admin/LevelIncomeReportComponent.vue";
import DirectIncomeReportComponent from "./../components/admin/DirectIncomeReportComponent.vue";
import RoiIncomeReportComponent from "./../components/admin/RoiIncomeReportComponent.vue";
import LevelViewReportComponent from "./../components/admin/LevelViewReportComponent.vue";
import TeamInvestmentReportComponent from "./../components/admin/TeamInvestmentReportComponent.vue";
import PendingWithdrawalReportComponent from "./../components/admin/PendingWithdrawalReportComponent.vue";
import ConfirmWithdrawalReportComponent from "./../components/admin/ConfirmWithdrawalReportComponent.vue";
import RejectedWithdrawalReportComponent from "./../components/admin/RejectedWithdrawalReportComponent.vue";
/*import VerifyWithdrawalReportComponent from "./../components/admin/VerifyWithdrawalReportComponent.vue";*/
import DepositAddressTransactionComponent from "./../components/admin/DepositAddressTransactionComponent.vue";
import AddMemberPMComponent from "./../components/admin/AddMemberPMComponent.vue";
import UserPerfectMoneyReportComponent from "./../components/admin/UserPerfectMoneyReportComponent.vue";
import PendingUserPerfectMoneyReportComponent from "./../components/admin/PendingUserPerfectMoneyReportComponent.vue";
import ConfirmAddressTransactionComponent from "./../components/admin/ConfirmAddressTransactionComponent.vue";
import DepositTransactionsPendingComponent from "./../components/admin/DepositTransactionsPendingComponent.vue";
import AllTransactionsComponent from "./../components/admin/AllTransactionsComponent.vue";
import AddManageMacComponent from "./../components/admin/AddManageMacComponent.vue";
import AddTopUpComponent from "./../components/admin/AddTopUpComponent.vue";
import PendingTopUpRequestComponent from "./../components/admin/PendingTopUpRequestComponent.vue";
import RejectTopUpRequestComponent from "./../components/admin/RejectTopUpRequestComponent.vue";
import ApproveTopUpRequestComponent from "./../components/admin/ApproveTopUpRequestComponent.vue";

import AddDeductionComponent from "./../components/admin/AddDeductionComponent.vue";
import AdminDeductionReportComponent from "./../components/admin/AdminDeductionReportComponent.vue";
import FreeTopUpComponent from "./../components/admin/FreeTopUpComponent.vue";
import TopUpReportComponent from "./../components/admin/TopUpReportComponent.vue";
import AdminFundReportComponent from "./../components/admin/AdminFundReportComponent.vue";
import RemoveFundReportComponent from "./../components/admin/RemoveFundReportComponent.vue";
import ManageNewsComponent from "./../components/admin/ManageNewsComponent.vue";
import AddNewsComponent from "./../components/admin/AddNewsComponent.vue";
import EditNewsComponent from "./../components/admin/EditNewsComponent.vue";
import SupportChatComponent from "./../components/admin/SupportChatComponent.vue";
import SubadminReportComponent from "./../components/admin/SubadminReportComponent.vue";
import AssignRightsComponent from "./../components/admin/AssignRightsComponent.vue";
import AssignRightsReportComponent from "./../components/admin/AssignRightsReportComponent.vue";
import TeamViewComponent from "./../components/admin/TeamViewComponent.vue";
import QualifiedUserComponent from "./../components/admin/QualifiedUserComponent.vue";
import BankReportComponent from "./../components/admin/BankReportComponent.vue";
import BankLogComponent from "./../components/admin/BankLogComponent.vue";
import DownlineUserReportComponent from "./../components/admin/DownlineUserReportComponent.vue";
import CarryBvReportComponent from "./../components/admin/CarryBvReportComponent.vue";
import RepurchaseCarryBvReportComponent from "./../components/admin/RepurchaseCarryBvReportComponent.vue";
import WithdrawRequestReportComponent from "./../components/admin/WithdrawRequestReportComponent.vue";
import WithdrawHistoryReportComponent from "./../components/admin/WithdrawHistoryReportComponent.vue";
import VerifyWithdrawalReportComponent from "./../components/admin/VerifyWithdrawalReportComponent.vue";
import PendingKYCComponent from "./../components/admin/PendingKYCComponent.vue";
import ApprovedKYCComponent from "./../components/admin/ApprovedKYCComponent.vue";
import RejectedKYCComponent from "./../components/admin/RejectedKYCComponent.vue";
import ManageMeetingComponent from "./../components/admin/ManageMeetingComponent.vue";
import ManageWhatsNewComponent from "./../components/admin/ManageWhatsNewComponent.vue";
import ManagePopupComponent from "./../components/admin/ManagePopupComponent.vue";
import ManageGalleryComponent from "./../components/admin/ManageGalleryComponent.vue";
import ManageVideosComponent from "./../components/admin/ManageVideosComponent.vue";
import ManageSeminarsComponent from "./../components/admin/ManageSeminarsComponent.vue";
import WalletReportComponent from "./../components/admin/WalletReportComponent.vue";
import ChangePasswordComponent from "./../components/admin/ChangePasswordComponent.vue";
import ChangeTrPasswordComponent from "./../components/admin/ChangeTrPasswordComponent.vue";
import RewardAceiverComponent from "./../components/admin/RewardAceiverComponent.vue";
import PendingTicketsComponent from "./../components/admin/PendingTicketsComponent.vue";
import ClosedTicketsComponent from "./../components/admin/ClosedTicketsComponent.vue";
import ManagePinComponent from "./../components/admin/ManagePinComponent.vue";
import AdminTransferPinReportComponent from "./../components/admin/AdminTransferPinReportComponent.vue";
import UsedPinReportComponent from "./../components/admin/UsedPinReportComponent.vue";
import UnusedPinReportComponent from "./../components/admin/UnusedPinReportComponent.vue";
import DeletePinReportComponent from "./../components/admin/DeletePinReportComponent.vue";
import PendingPinRequestComponent from "./../components/admin/PendingPinRequestComponent.vue";
import TotalSellReportComponent from "./../components/admin/TotalSellReportComponent.vue";
import ProductWiseSellReportComponent from "./../components/admin/ProductWiseSellReportComponent.vue";
import ProductWisePurchaseReportComponent from "./../components/admin/ProductWisePurchaseReportComponent.vue";
import ProductWiseRepurchaseReportComponent from "./../components/admin/ProductWiseRepurchaseReportComponent.vue";
import SubBinaryIncomeReportComponent from "./../components/admin/SubBinaryIncomeReportComponent.vue";
import RepurchaseBinaryIncomeReportComponent from "./../components/admin/RepurchaseBinaryIncomeReportComponent.vue";
import SubRepurchaseBinaryIncomeReportComponent from "./../components/admin/SubRepurchaseBinaryIncomeReportComponent.vue";
import UsedTransferPinReportComponent from "./../components/admin/UsedTransferPinReportComponent.vue";
import UnusedTransferPinReportComponent from "./../components/admin/UnusedTransferPinReportComponent.vue";
import TotalPinReportComponent from "./../components/admin/TotalPinReportComponent.vue";
import RejectedPinRequestReportComponent from "./../components/admin/RejectedPinRequestReportComponent.vue";
import ApprovedPinRequestReportComponent from "./../components/admin/ApprovedPinRequestReportComponent.vue";
import TreeViewComponent from "./../components/admin/TreeViewComponent.vue";
import LevelRoiIncomeComponent from "./../components/admin/LevelRoiIncomeComponent.vue";
import UplineIncomeComponent from "./../components/admin/UplineIncomeComponent.vue";
import PendingPromotionalComponent from "./../components/admin/PendingPromotionalComponent.vue";
import ApprovedPromotionalComponent from "./../components/admin/ApprovedPromotionalComponent.vue";
import RejectedPromotionalComponent from "./../components/admin/RejectedPromotionalComponent.vue";
import PromotionalIncomeReportComponent from "./../components/admin/PromotionalIncomeReportComponent.vue";
import AddBTCAddressComponent from "./../components/admin/AddBTCAddressComponent.vue";
import PendingFundRequestComponent from './../components/admin/PendingFundRequestComponent.vue';
import AdminAddFundComponent from './../components/admin/AdminAddFundComponent.vue';
import RemoveFundComponent from './../components/admin/RemoveFundComponent.vue';
import ApprovedFundRequestComponent from './../components/admin/ApprovedFundRequestComponent.vue';
import RejectedFundRequestComponent from './../components/admin/RejectedFundRequestComponent.vue';
import OldTopupReportComponent from './../components/admin/OldTopupReportComponent.vue';
import WithdrawalPaymentComponent from './../components/admin/WithdrawalPaymentComponent.vue';
import AddUpdateGalleryComponent from './../components/admin/AddUpdateGalleryComponent.vue';
import AddUpdateVideoComponent from './../components/admin/AddUpdateVideoComponent.vue';
import BinaryIncomeReportComponent from './../components/admin/BinaryIncomeReportComponent.vue';
import MatchingBonusIncomeReportComponent from './../components/admin/MatchingBonusIncomeReportComponent.vue';
import AchievedMatchingBonusIncomeReportComponent from './../components/admin/AchievedMatchingBonusIncomeReportComponent.vue';
import AddPowerComponent from './../components/admin/AddPowerComponent.vue';
import PowerReportComponent from './../components/admin/PowerReportComponent.vue';
import OtpComponent from './../components/admin/OtpComponent.vue';
import FranchiseIncomeReport from './../components/admin/FranchiseIncomeReport.vue';
import FranchiseUserReport from './../components/admin/FranchiseUserReport.vue';
import AccountWalletComponent from './../components/admin/AccountWalletComponent.vue';
import DexAccountWalletComponent from './../components/admin/DexAccountWalletComponent.vue';
import EnquiryReportComponent from './../components/admin/EnquiryReportComponent.vue';
import SendEnquiryReplyComponent from './../components/admin/SendEnquiryReplyComponent.vue';
import EnquiryReplyReportComponent from './../components/admin/EnquiryReplyReportComponent.vue';
import UserLinkReportComponent from './../components/admin/UserLinkReportComponent.vue';
import FreedomClubReportComponent from './../components/admin/FreedomClubReportComponent.vue';
import DailyBussinessReportComponent from './../components/admin/DailyBussinessReportComponent.vue';
import SuperMatchingIncomeReportComponent from './../components/admin/SuperMatchingIncomeReportComponent.vue';
import AddRankComponent from './../components/admin/AddRankComponent.vue';
import AddRankReportComponent from './../components/admin/AddRankReportComponent.vue';
import AddRankPowerUplineComponent from './../components/admin/AddRankPowerUplineComponent.vue';
import AddRankPowerUplineReportComponent from './../components/admin/AddRankPowerUplineReportComponent.vue';
import AddFranchisee from './../components/admin/AddFranchisee.vue';
import StructureReportComponent from './../components/admin/StructureReportComponent.vue';
import ContestAchievementReportComponent from './../components/admin/ContestAchievementReportComponent.vue';
import SettingAddFundComponent from './../components/admin/SettingAddFundComponent.vue';
import SettingFundReportComponent from './../components/admin/SettingFundReportComponent.vue';
import RankQualifyReportComponent from './../components/admin/RankQualifyReportComponent.vue';

import ChangeUseridComponent from './../components/admin/ChangeUseridComponent.vue';
import ChangeUseridReportComponent from './../components/admin/ChangeUseridReportComponent.vue';
import AddBussinesComponent from './../components/admin/AddBussinesComponent.vue';
import AddBussinessReportComponent from './../components/admin/AddBussinessReportComponent.vue';
import AddBussinesUplineComponent from './../components/admin/AddBussinesUplineComponent.vue';
import AddBussinessUplineReportComponent from './../components/admin/AddBussinessUplineReportComponent.vue';
import SettingRemoveFundComponent from './../components/admin/SettingRemoveFundComponent.vue';
import SettingRemoveFundReportComponent from './../components/admin/SettingRemoveFundReportComponent.vue';
import FundTransferReportComponent from './../components/admin/FundTransferReportComponent.vue';
import WalletTransactionLogComponent from './../components/admin/WalletTransactionLogComponent.vue';
import DexToPurchaseTraansferReportComponent from './../components/admin/DexToPurchaseTraansferReportComponent.vue';
import BalanceTransferRequestReport from './../components/admin/BalanceTransferRequestReport.vue';
import PurchaseBalanceTransferRequestReport from './../components/admin/PurchaseBalanceTransferRequestReport.vue';
import WithdrawSettingsComponent from './../components/admin/WithdrawSettingsComponent.vue';

import RemoveDxWalletFundComponent from './../components/admin/RemoveDxWalletFundComponent.vue';
import RemoveDxWalletFundReportComponent from './../components/admin/RemoveDxWalletFundReportComponent.vue';
import SummaryBySponsorIDComponent from './../components/admin/SummaryBySponsorIDComponent.vue';

import CategoryComponent from './../components/admin/CategoryComponent.vue';
import AddCategoryComponent from './../components/admin/AddCategoryComponent.vue';
import ManageEcommerceProductComponent from './../components/admin/ManageEcommerceProductComponent.vue';
import AddUpdateEcommerceProductComponent from './../components/admin/AddUpdateEcommerceProductComponent.vue';
import AddMultipImagesForEproduct from './../components/admin/AddMultipImagesForEproduct.vue';
import UserOrderComponent from './../components/admin/UserOrderComponent.vue';
import PendingOrderComponent from './../components/admin/PendingOrderComponent.vue';
import ViewOrderDetails from './../components/admin/ViewOrderDetails.vue';
import DeliveredOrderComponent from './../components/admin/DeliveredOrderComponent.vue';
import CancelledOrderComponent from './../components/admin/CancelledOrderComponent.vue';

import RankBySponsorIDComponent from './../components/admin/RankBySponsorIDComponent.vue';
import AddRankPowerComponent from './../components/admin/AddRankPowerComponent.vue';
import AddRankPowerReportComponent from './../components/admin/AddRankPowerReportComponent.vue';

//---DownlineBusinessSetting
import DownlineBusinessSetting from './../components/admin/DownlineBusinessSetting.vue';
import BusinessSettingReport from './../components/admin/BusinessSettingReport.vue';
import BalanceSheetReportComponent from './../components/admin/BalanceSheetReportComponent.vue';
import WorkingToPurchaseTransferReportComponent from './../components/admin/WorkingToPurchaseTransferReportComponent.vue';
import UnblockUsersReportComponent from "./../components/admin/UnblockUsersReportComponent.vue";
import IcoAdminSendRep from "./../components/admin/IcoAdminSendRep.vue";
import UserToUserSendCoinReports from "./../components/admin/UserToUserSendCoinReports.vue";
import SendCoinToUser from "./../components/admin/SendCoinToUser.vue";
import BuyActivePhaseCoin from "./../components/admin/BuyActivePhaseCoin.vue";
import AdminBuyCoinRep from "./../components/admin/AdminBuyCoinRep.vue";
import IcoOnOff from "./../components/admin/IcoOnOff.vue";
import IcoUserBuyRep from "./../components/admin/IcoUserBuyRep.vue";
import IcoPhasesList from "./../components/admin/IcoPhasesList.vue";
import UserBulkEditComponent from "./../components/admin/UserBulkEditComponent.vue";
import UserBulkEditReportComponent from "./../components/admin/UserBulkEditReportComponent.vue";

import FundInvoiceComponent from './../components/admin/FundInvoiceComponent.vue';
import FakeTopupReportComponent from './../components/admin/FakeTopupReportComponent.vue';
import FakeWithdrawReportComponent from './../components/admin/FakeWithdrawReportComponent.vue';
import DailyReportComponent from './../components/admin/DailyReportComponent.vue';
import InvalidLoginUsersReportComponent from './../components/admin/InvalidLoginUsersReportComponent.vue';
import SecurityDashboardComponent from './../components/admin/SecurityDashboardComponent.vue';

// Flight booking 
import ManageFlightLogoandName from './../components/admin/ManageFlightLogoandName.vue';
import PendingBookingComponent from './../components/admin/PendingBookingComponent.vue';
import ConfirmBookingComponent from './../components/admin/ConfirmBookingComponent.vue';
import CancelBookingComponent from './../components/admin/CancelBookingComponent.vue';

// Hotel Booking
import PendingHotelBookingComponent from './../components/admin/PendingHotelBookingComponent.vue';
import ConfirmHotelBookingComponent from './../components/admin/ConfirmHotelBookingComponent.vue';
import CancelHotelBookingComponent from './../components/admin/CancelHotelBookingComponent.vue';

import FundWalletAddRemoveFundComponent from './../components/admin/FundWalletAddRemoveFundComponent.vue';
import DynamicCurrencyComponent from './../components/admin/DynamicCurrencyComponent.vue';
import AddNewCurrencyComponent from './../components/admin/AddNewCurrencyComponent.vue';
import EditCurrencyComponent from './../components/admin/EditCurrencyComponent.vue';

import AdminSettingAddFundComponent from './../components/admin/AdminSettingAddFundComponent.vue';
import AdminSettingFundReportComponent from "./../components/admin/AdminSettingFundReportComponent.vue";
import AllTransactionsIPReportComponent from "./../components/admin/AllTransactionsIPReportComponent.vue";
import AddBusinessBVComponent from './../components/admin/AddBusinessBVComponent.vue';
import AddRemoveBussinessUplineReportComponent from './../components/admin/AddRemoveBussinessUplineReportComponent.vue';

import Guard from "./middleware";

export default new VueRouter({
    //hashbang: false,
    //mode:'history',
    base: "/admin/",
    routes: [{
            path: "/login",
            component: LoginComponent,
            name: "login",
            beforeEnter: Guard.guest
        },
        {
            path: '/otp',
            component: OtpComponent,
            name: 'otp',
            beforeEnter: Guard.guest
        },
        {
            path: "/dashboard",
            component: DashboardComponent,
            name: "dashboard",
            beforeEnter: Guard.auth
        },
        {
            path: "/account-wallet",
            component: AccountWalletComponent,
            name: "account-wallet",
            beforeEnter: Guard.auth
        },
        {
            // path: "/dex-account-wallet",
            path: "/withdraw-account-wallet",
            component: DexAccountWalletComponent,
            name: "withdraw-account-wallet",
            beforeEnter: Guard.auth
        },
        {
            path: "/enquiry-reports",
            component: EnquiryReportComponent,
            name: "enquiry-reports",
            beforeEnter: Guard.auth
        },
        {
            path: '/send-reply',
            component: SendEnquiryReplyComponent,
            name: 'send-reply',
            beforeEnter: Guard.auth
        },

        {
            path: '/send-reply-reports',
            component: EnquiryReplyReportComponent,
            name: 'send-reply-reports',
            beforeEnter: Guard.auth
        },
        {
            path: '/user-link-report',
            component: UserLinkReportComponent,
            name: 'user-link-report',
            beforeEnter: Guard.auth
        },

        {
            path: "/product/product-report",
            component: ProductReportComponent,
            name: "productreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/manage-user-account",
            component: ManageUserComponent,
            name: "manageuser",
            beforeEnter: Guard.auth
        },
        {
            path: "/user-franchise-report",
            component: ManageUserFranchiseComponent,
            name: "manageuserfranchise",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/edit-user-profile/:id",
            component: EditUserProfileComponent,
            name: "edituserprofile",
            beforeEnter: Guard.auth
        },
        {
            path: "/setting/edit-currency/:id",
            component: EditCurrencyComponent,
            name: "editcurrency",
            beforeEnter: Guard.auth
        },
        {
            path: "/edit-withdrawal-mode/:id",
            component: EditWithdrawalModeComponent,
            name: "editwithdrawalmode",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/change-password",
            component: ChangeUserPasswordComponent,
            name: "changepassword",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/franchise-user-report",
            component: FranchiseUserReport,
            name: "franchiseuserreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/level-income",
            component: LevelIncomeReportComponent,
            name: "binaryincomereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/level-roi-income",
            component: LevelRoiIncomeComponent,
            name: "levelRoiincomereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/upline-income",
            component: UplineIncomeComponent,
            name: "uplinencomereport",
            beforeEnter: Guard.auth
        },

        {
            path: "/e-wallet/direct-income",
            component: DirectIncomeReportComponent,
            name: "directincomereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/roi-income",
            component: RoiIncomeReportComponent,
            name: "roiincome",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/binary-income",
            component: BinaryIncomeReportComponent,
            name: "binaryincome",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/achieved-matching-bonus-income",
            component: AchievedMatchingBonusIncomeReportComponent,
            name: "achievedmatchingbonusincomereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/matching-bonus-income",
            component: MatchingBonusIncomeReportComponent,
            name: "matchingbonusincomereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/franchise-income",
            component: FranchiseIncomeReport,
            name: "franchiseincomereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-genealogy/level-view",
            component: LevelViewReportComponent,
            name: "levelview",
            beforeEnter: Guard.auth
        },
        {
            path: "/investment-report/team-investment",
            component: TeamInvestmentReportComponent,
            name: "teaminvestment",
            beforeEnter: Guard.auth
        },
        {
            path: "/withdrawal/pending-withdrawal-report",
            component: PendingWithdrawalReportComponent,
            name: "pendingwithdrawalreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/withdrawal/confirm-withdrawal-report",
            component: ConfirmWithdrawalReportComponent,
            name: "confirmwithdrawalreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/withdrawal/rejected-withdrawal-report",
            component: RejectedWithdrawalReportComponent,
            name: "rejectedwithdrawalreport",
            beforeEnter: Guard.auth
        },
        /* {
         path: "/verify-withdrawal-report",
          component: VerifyWithdrawalReportComponent,
          name: "verifywithdrawalreport",
          beforeEnter: Guard.auth
        },*/
        {
            path: "/transactions/deposit-address-transaction",
            component: DepositAddressTransactionComponent,
            name: "depositaddresstransaction",
            beforeEnter: Guard.auth
        },
        {
            path: "/user-perfectmoneyreport",
            component: UserPerfectMoneyReportComponent,
            name: "userperfectmoneyreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/pending-user-perfectmoneyreport",
            component: PendingUserPerfectMoneyReportComponent,
            name: "pendinguserperfectmoneyreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/coinpayment/confirm-address-transaction",
            component: ConfirmAddressTransactionComponent,
            name: "confirmaddresstransaction",
            beforeEnter: Guard.auth
        },
        {
            path: "/transactions/deposit-transactions-pending",
            component: DepositTransactionsPendingComponent,
            name: "deposittransactionspending",
            beforeEnter: Guard.auth
        },
        {
            path: "/transactions/all-transactions",
            component: AllTransactionsComponent,
            name: "alltransactions",
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-mac/add-manage-mac",
            component: AddManageMacComponent,
            name: "addmanagemac",
            beforeEnter: Guard.auth
        },
        {
            path: "/top-up/add-top-up",
            component: AddTopUpComponent,
            name: "addtopup",
            beforeEnter: Guard.auth
        },
        {
            path: "/top-up/pending-topup-request",
            component: PendingTopUpRequestComponent,
            name: "pendingtopuprequest",
            beforeEnter: Guard.auth
        },
        {
            path: "/top-up/approve-topup-request",
            component: ApproveTopUpRequestComponent,
            name: "approvetopuprequest",
            beforeEnter: Guard.auth
        },
        {
            path: "/top-up/reject-topup-request",
            component: RejectTopUpRequestComponent,
            name: "rejecttopuprequest",
            beforeEnter: Guard.auth
        },
        /*{
          path: "/deduction/add-deduction",
          component: AddDeductionComponent,
          name: "adddeduction",
          beforeEnter: Guard.auth
        },*/
        {
            path: "/top-up/free-top-up",
            component: FreeTopUpComponent,
            name: "freetopup",
            beforeEnter: Guard.auth
        },
        {
            path: "/top-up/top-up-report",
            component: TopUpReportComponent,
            name: "topupreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/admin-add-fundreport",
            component: AdminFundReportComponent,
            name: "addfundreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/admin-remove-fundreport",
            component: RemoveFundReportComponent,
            name: "removefundreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/setting-add-fundreport",
            component: SettingFundReportComponent,
            name: "settingfundreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/admin-add-deduction-report",
            component: AdminDeductionReportComponent,
            name: "adddeductionreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/top-up/old-top-up-report",
            component: OldTopupReportComponent,
            name: "oldtopupreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/edit-profile-report",
            component: EditUserProfileReportComponent,
            name: "editprofilereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/block-users-report",
            component: BlockUsersReportComponent,
            name: "blockusersreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/user-btc-address-report",
            component: UserBTCAddressReportComponent,
            name: "userbtcaddressreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-theme/news",
            component: ManageNewsComponent,
            name: "managenews",
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-theme/add-news",
            component: AddNewsComponent,
            name: "addnews",
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-theme/editnews/:id",
            component: EditNewsComponent,
            name: "editnews",
            beforeEnter: Guard.auth
        },
        {
            path: "/support/support-chat",
            component: SupportChatComponent,
            name: "supportchat",
            beforeEnter: Guard.auth
        },
        {
            path: "/sub-admin/create-sub-admin",
            component: SubadminReportComponent,
            name: "createsubadmin",
            beforeEnter: Guard.auth
        },
        {
            path: "/sub-admin/assign-right",
            component: AssignRightsComponent,
            name: "assignright",
            beforeEnter: Guard.auth
        },
        {
            path: "/sub-admin/assign-rights-report",
            component: AssignRightsReportComponent,
            name: "assignrightsreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/user-profile/:id",
            component: UserProfileComponent,
            name: "userprofile",
            beforeEnter: Guard.auth
        },

        {
            path: "/user/tree-view",
            component: TreeViewComponent,
            name: "treeview",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/total-team-view",
            component: TeamViewComponent,
            name: "teamviewreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/qualified-user-list",
            component: QualifiedUserComponent,
            name: "qualifieduserreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/banks-report",
            component: BankReportComponent,
            name: "bankreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/withdrawal-payment",
            component: WithdrawalPaymentComponent,
            name: "withdrawalpayment",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/banks-logs-report",
            component: BankLogComponent,
            name: "banklogreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/downline-users-report",
            component: DownlineUserReportComponent,
            name: "downlineuserreport",
            beforeEnter: Guard.auth
        }, {
            path: "/downline-business-setting",
            component: DownlineBusinessSetting,
            name: "downline-business-setting",
            beforeEnter: Guard.auth
        }, {
            path: "/business-setting-report",
            component: BusinessSettingReport,
            name: "business-setting-report",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/carry-bv-report",
            component: CarryBvReportComponent,
            name: "carrybvreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/repurchase-carry-bv-report",
            component: RepurchaseCarryBvReportComponent,
            name: "repurchasecarrybvreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/withdrawal/withdrawal-request",
            component: WithdrawRequestReportComponent,
            name: "withdrawrequestreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/withdrawal/withdrawal-history",
            component: WithdrawHistoryReportComponent,
            name: "withdrawhistoryreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/withdrawal/verified-withdrawal",
            component: VerifyWithdrawalReportComponent,
            name: "verifiedwithdrawalreport",
            beforeEnter: Guard.hasAccess
        },
        {
            path: "/kyc/pending-kyc",
            component: PendingKYCComponent,
            name: "pendingkycreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/kyc/approved-kyc",
            component: ApprovedKYCComponent,
            name: "approvedkycreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/kyc/rejected-kyc",
            component: RejectedKYCComponent,
            name: "rejectedkycreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-theme/meeting",
            component: ManageMeetingComponent,
            name: "managemeeting",
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-theme/whats-new",
            component: ManageWhatsNewComponent,
            name: "managewhatsnew",
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-theme/pop-up",
            component: ManagePopupComponent,
            name: "managepopup",
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-theme/seminars",
            component: ManageSeminarsComponent,
            name: "manageseminars",
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-theme/videos",
            component: ManageVideosComponent,
            name: "managevideos",
            beforeEnter: Guard.auth
        },
        {
            path: '/manage-theme/videos/add-video/:id?',
            component: AddUpdateVideoComponent,
            name: 'addvideo',
            beforeEnter: Guard.auth
        },
        {
            path: "/manage-theme/gallery",
            component: ManageGalleryComponent,
            name: "managegallery",
            beforeEnter: Guard.auth
        },
        {
            path: '/manage-theme/gallery/add-gallery/:id?',
            component: AddUpdateGalleryComponent,
            name: 'addgallery',
            beforeEnter: Guard.auth
        },
        {
            path: "/admin/wallet-report",
            component: WalletReportComponent,
            name: "walletreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/admin/change-password",
            component: ChangePasswordComponent,
            name: "changepasswordreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/admin/change-transaction-password",
            component: ChangeTrPasswordComponent,
            name: "changetrpasswordreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/reward-achievers/reward-achievers-report",
            component: RewardAceiverComponent,
            name: "rewardacheiverreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/product/total-sell-report/:product_id?",
            component: TotalSellReportComponent,
            name: "totalsellreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/product/product-wise-sell-report",
            component: ProductWiseSellReportComponent,
            name: "productwisesellreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/product/product-wise-joining-report",
            component: ProductWisePurchaseReportComponent,
            name: "productwisejoiningreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/product/product-wise-repurchase-report",
            component: ProductWiseRepurchaseReportComponent,
            name: "productwiserepurchasereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/sub-binary-income/:id/:payout_no",
            component: SubBinaryIncomeReportComponent,
            name: "subbinaryincomereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/repurchase-binary-income-report",
            component: RepurchaseBinaryIncomeReportComponent,
            name: "repurchasebinaryincomereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/sub-repurchase-binary-income-report/:id/:payout_no",
            component: SubRepurchaseBinaryIncomeReportComponent,
            name: "subrepurchasebinaryincomereport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/used-transfer-pin-report",
            component: UsedTransferPinReportComponent,
            name: "usedtransferpinreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/unused-transfer-pin-report",
            component: UnusedTransferPinReportComponent,
            name: "unusedtransferpinreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/total-pin-report",
            component: TotalPinReportComponent,
            name: "totalpinreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/rejected-pin-request",
            component: RejectedPinRequestReportComponent,
            name: "rejectedpinrequest",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/approved-pin-request",
            component: ApprovedPinRequestReportComponent,
            name: "approvedpinrequest",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/manage-pin",
            component: ManagePinComponent,
            name: "managepin",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/admin-transfer-pin-history",
            component: AdminTransferPinReportComponent,
            name: "admintransferpinhistory",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/used-pin-history",
            component: UsedPinReportComponent,
            name: "usedpinhistory",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/unused-pin-history",
            component: UnusedPinReportComponent,
            name: "unusedpinhistory",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/delete-pin-history",
            component: DeletePinReportComponent,
            name: "deletepinhistory",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-pin/pending-pin-request",
            component: PendingPinRequestComponent,
            name: "pendingpinrequest",
            beforeEnter: Guard.auth
        },
        {
            path: "/support/pending-tickets",
            component: PendingTicketsComponent,
            name: "pendingtickets",
            beforeEnter: Guard.auth
        },
        {
            path: "/support/closed-tickets",
            component: ClosedTicketsComponent,
            name: "closedtickets",
            beforeEnter: Guard.auth
        },
        {
            path: "/promotional/pending-promotional",
            component: PendingPromotionalComponent,
            name: "pending-promotional",
            beforeEnter: Guard.auth
        },
        {
            path: "/promotional/approved-promotional",
            component: ApprovedPromotionalComponent,
            name: "approved-promotional",
            beforeEnter: Guard.auth
        },
        {
            path: "/promotional/rejected-promotional",
            component: RejectedPromotionalComponent,
            name: "rejected-promotional",
            beforeEnter: Guard.auth
        },
        {
            path: "/promotional/promotional-income",
            component: PromotionalIncomeReportComponent,
            name: "promotional-income",
            beforeEnter: Guard.auth
        },
        {
            path: "/add-btc-address",
            component: AddBTCAddressComponent,
            name: "add-btc-address",
            beforeEnter: Guard.auth
        },
        {
            path: '/manage-power/add-power',
            component: AddPowerComponent,
            name: 'add-power',
            beforeEnter: Guard.auth
        },
        {
            path: '/manage-power/power-report',
            component: PowerReportComponent,
            name: 'power-report',
            beforeEnter: Guard.auth
        },
        {
            path: "*",
            component: LoginComponent,
            redirect: "login",
            beforeEnter: Guard.guest
        },
        {
            path: '/pending-fund-request',
            component: PendingFundRequestComponent,
            name: 'pending-fund-request',
            beforeEnter: Guard.auth
        },

        {
            path: '/admin-add-fund',
            component: AdminAddFundComponent,
            name: 'adminaddfund',
            beforeEnter: Guard.auth
        },
        {
            path: '/admin-remove-fund',
            component: RemoveFundComponent,
            name: 'adminremovefund',
            beforeEnter: Guard.auth
        },
        {
            path: '/setting-add-fund',
            component: SettingAddFundComponent,
            name: 'settingaddfund',
            beforeEnter: Guard.auth
        },
        {
            path: '/setting-remove-fund',
            component: SettingRemoveFundComponent,
            name: 'settingremovefund',
            beforeEnter: Guard.auth
        },
        {
            path: '/setting-remove-fund-report',
            component: SettingRemoveFundReportComponent,
            name: 'settingremovefundreport',
            beforeEnter: Guard.auth
        },
        {
            path: '/approved-fund-request',
            component: ApprovedFundRequestComponent,
            name: 'approved-fund-request',
            beforeEnter: Guard.auth
        },
        {
            path: '/rejected-fund-request',
            component: RejectedFundRequestComponent,
            name: 'rejected-fund-request',
            beforeEnter: Guard.auth
        },
        {
            path: '/add-franchisee',
            component: AddFranchisee,
            name: 'add-franchisee',
            beforeEnter: Guard.auth
        }, {
            path: '/franchise-users',
            component: AddFranchisee,
            name: 'franchise-users',
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/super-matching-income",
            component: SuperMatchingIncomeReportComponent,
            name: "SuperMatchingIncome",
            beforeEnter: Guard.auth
        },
        {
            path: "/e-wallet/freedom-club-report",
            component: FreedomClubReportComponent,
            name: "FreedomClubReport",
            beforeEnter: Guard.auth
        },
        {
            path: "/top-up/daily-bussiness-report",
            component: DailyBussinessReportComponent,
            name: "DailyBussinessReport",
            beforeEnter: Guard.auth
        },
        {
            path: "/rank/add-rank",
            component: AddRankComponent,
            name: "AddRank",
            beforeEnter: Guard.auth
        },
        {
            path: "/rank/add-rank-report",
            component: AddRankReportComponent,
            name: "AddRankReport",
            beforeEnter: Guard.auth
        },
        {
            path: "/rank/qualify-rank-report",
            component: RankQualifyReportComponent,
            name: "QualifyRankReport",
            beforeEnter: Guard.auth
        },
        {
            path: "/user/structure-report",
            component: StructureReportComponent,
            name: "structure-report",
            beforeEnter: Guard.auth
        },
        {
            path: "/contest-achievement-report",
            component: ContestAchievementReportComponent,
            name: "contest-achievement-report",
            beforeEnter: Guard.auth
        },
        {
            path: "/add-bussiness",
            component: AddBussinesComponent,
            name: "addbussiness",
            beforeEnter: Guard.auth
        },
        {
            path: "/add-bussiness-report",
            component: AddBussinessReportComponent,
            name: "addbussiness-report",
            beforeEnter: Guard.auth
        },
        {
            path: "/add-bussiness-upline",
            component: AddBussinesUplineComponent,
            name: "addbussinessupline",
            beforeEnter: Guard.auth
        },
        {
            path: '/add-bussiness-upline-report',
            component: AddBussinessUplineReportComponent,
            name: 'addbussiness-upline-report',
            beforeEnter: Guard.auth
        },
        {
            path: "/fund-transfer-report",
            component: FundTransferReportComponent,
            name: "fundtransferreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/wallet-transaction-log",
            component: WalletTransactionLogComponent,
            name: "wallettransactionlog",
            beforeEnter: Guard.auth
        },
        {
            path: "/working-to-purchase-transfer-report",
            component: DexToPurchaseTraansferReportComponent,
            name: "workingtopurchasetransferreport",
            beforeEnter: Guard.auth
        },
        {
            path: "/balance-transfer-request",
            component: BalanceTransferRequestReport,
            name: "balancetransferrequest",
            beforeEnter: Guard.auth
        },

        {
            path: "/purchase-balance-transfer-request",
            component: PurchaseBalanceTransferRequestReport,
            name: "purchasebalancetransferrequest",
            beforeEnter: Guard.auth
        },

        {
            path: "/user/change-userid",
            component: ChangeUseridComponent,
            name: "change-userid",
            beforeEnter: Guard.auth
        },

        {
            path: "/user/change-userid-report",
            component: ChangeUseridReportComponent,
            name: "change-userid-report",
            beforeEnter: Guard.auth
        },
        {
            path: '/admin-remove-dx-wallet-fund',
            component: RemoveDxWalletFundComponent,
            name: 'adminremovedxwalletfund',
            beforeEnter: Guard.auth
        },
        {
            path: '/admin-remove-dx-wallet-fund-report',
            component: RemoveDxWalletFundReportComponent,
            name: 'adminremovedxwalletfundreport',
            beforeEnter: Guard.auth
        },
        {
            path: '/top-leader-summary-report',
            component: SummaryBySponsorIDComponent,
            name: 'topleadersummaryreport',
            beforeEnter: Guard.auth
        },
        {
            path: '/categories',
            component: CategoryComponent,
            name: 'categories',
            beforeEnter: Guard.auth
        },
        {
            path: '/add-category/:id?',
            component: AddCategoryComponent,
            name: 'add-category',
            beforeEnter: Guard.auth
        },
        {
            path: '/product/product-reports',
            component: ManageEcommerceProductComponent,
            name: 'productreports',
            beforeEnter: Guard.auth
        },
        {
            path: '/product/add-ecommerce-product/:id?',
            component: AddUpdateEcommerceProductComponent,
            name: 'addecommerceproduct',
            beforeEnter: Guard.auth
        },
        {
            path: '/settings/add-currency',
            component: AddNewCurrencyComponent,
            name: 'addnewcurrency',
            beforeEnter: Guard.auth
        },
        {
            path: '/product/add-images/:id',
            component: AddMultipImagesForEproduct,
            name: 'product/add-images',
            beforeEnter: Guard.auth
        },
        {
            path: '/order/all',
            component: UserOrderComponent,
            name: 'allorder',
        },
        {
            path: '/order/pending',
            component: PendingOrderComponent,
            name: 'pendingorder',
        },
        {
            path: '/view-order-details/:id',
            component: ViewOrderDetails,
            name: 'view-order-details',
        },
        {
            path: '/order/delivered',
            component: DeliveredOrderComponent,
            name: 'deliveredorder',
        },

        {
            path: '/order/cancelled',
            component: CancelledOrderComponent,
            name: 'cancelledorder',
        },
        {
            path: '/rank/rank_by_user',
            component: RankBySponsorIDComponent,
            name: 'RankByUser',
            beforeEnter: Guard.auth
        },

        {
            path: '/rank/add_rank_power',
            component: AddRankPowerComponent,
            name: 'AddRankPower',
            beforeEnter: Guard.auth
        },

        {
            path: '/rank/add-rank-power-report',
            component: AddRankPowerReportComponent,
            name: 'AddRankPowerReport',
            beforeEnter: Guard.auth
        },
        {
            path: '/rank/add_rank_power_upline',
            component: AddRankPowerUplineComponent,
            name: 'AddRankPowerUpline',
            beforeEnter: Guard.auth
        },

        {
            path: '/rank/add-rank-power-upline-report',
            component: AddRankPowerUplineReportComponent,
            name: 'AddRankPowerUplineReport',
            beforeEnter: Guard.auth
        },

        {
            path: '/balance-sheet-report',
            component: BalanceSheetReportComponent,
            name: 'balancesheetreport',
            beforeEnter: Guard.auth
        },

        {
            path: "/user/unblock-users-report",
            component: UnblockUsersReportComponent,
            name: "unblockusersreport",
            beforeEnter: Guard.auth
        },
        ///-------- Coin ICO--------

        {
            path: '/send-coin-to-user',
            component: SendCoinToUser,
            name: 'send-coin-to-user',
            beforeEnter: Guard.auth
        }, {
            path: '/ico-on-off',
            component: IcoOnOff,
            name: 'ico-on-off',
            beforeEnter: Guard.auth
        }, {
            path: '/ico-user-buy-rep',
            component: IcoUserBuyRep,
            name: 'ico-user-buy-rep',
            beforeEnter: Guard.auth
        }, {
            path: '/ico-admin-send-rep',
            component: IcoAdminSendRep,
            name: 'ico-admin-send-rep',
            beforeEnter: Guard.auth
        },
        {
            path: '/ico-phases-list',
            component: IcoPhasesList,
            name: 'ico-phases-list',
            beforeEnter: Guard.auth
        },
        {
            path: '/manage-flight-logo',
            component: ManageFlightLogoandName,
            name: 'manage-flight-logo',
            beforeEnter: Guard.auth,
        },
        {
            path: '/flight/pending-booking',
            component: PendingBookingComponent,
            name: 'pending-booking-report',
            beforeEnter: Guard.auth,
        },
        {
            path: '/flight/confirm-booking',
            component: ConfirmBookingComponent,
            name: 'confirm-booking-report',
            beforeEnter: Guard.auth,
        },
        {
            path: '/flight/cancel-booking',
            component: CancelBookingComponent,
            name: 'cancel-booking-report',
            beforeEnter: Guard.auth,
        },
        {
            path: '/hotel/pending-booking',
            component: PendingHotelBookingComponent,
            name: 'pending-hotelbooking-report',
            beforeEnter: Guard.auth,
        },
        {
            path: '/hotel/confirm-booking',
            component: ConfirmHotelBookingComponent,
            name: 'confirm-hotelbooking-report',
            beforeEnter: Guard.auth,
        },
        {
            path: '/hotel/cancel-booking',
            component: CancelHotelBookingComponent,
            name: 'cancel-hotelbooking-report',
            beforeEnter: Guard.auth,
        },
        {
            path: '/send-coin-to-user',
            component: SendCoinToUser,
            name: 'send-coin-to-user',
            beforeEnter: Guard.auth
        }, {
            path: '/buy-active-phase-coin',
            component: BuyActivePhaseCoin,
            name: 'buy-active-phase-coin',
            beforeEnter: Guard.auth
        }, {
            path: '/admin-buy-coin-rep',
            component: AdminBuyCoinRep,
            name: 'admin-buy-coin-rep',
            beforeEnter: Guard.auth
        },
        {
            path: '/ico-user-buy-rep',
            component: IcoUserBuyRep,
            name: 'ico-user-buy-rep',
            beforeEnter: Guard.auth
        },
        {
            path: '/ico-admin-send-rep',
            component: IcoAdminSendRep,
            name: 'ico-admin-send-rep',
            beforeEnter: Guard.auth
        }, {
            path: '/user-to-user-send-rep',
            component: UserToUserSendCoinReports,
            name: 'user-to-user-send-rep',
            beforeEnter: Guard.auth
        },
        {
            path: '/ico-phases-list',
            component: IcoPhasesList,
            name: 'ico-phases-list',
            beforeEnter: Guard.auth
        },
        {
            path: '/user/user-bulk-update',
            component: UserBulkEditComponent,
            name: 'user-bulk-update',
            beforeEnter: Guard.auth
        },
        {
            path: '/user/bulk-user-update-report',
            component: UserBulkEditReportComponent,
            name: 'bulk-user-update-report',
            beforeEnter: Guard.auth
        },
        {
            path: '/working-to-topup-report',
            component: WorkingToPurchaseTransferReportComponent,
            name: 'working-to-topup-report',
            beforeEnter: Guard.auth
        },
        {
            path: '/fund-wallet-add-remove',
            component: FundWalletAddRemoveFundComponent,
            name: 'fund-wallet-add-remove',
            beforeEnter: Guard.auth
        },
        {
            path: '/settings/update-withdraw-setting',
            component: WithdrawSettingsComponent,
            name: 'update-withdraw-setting',
            beforeEnter: Guard.auth
        },
        {
            path: '/settings/dynamic-currency-setting',
            component: DynamicCurrencyComponent,
            name: 'dynamic-currency-setting',
            beforeEnter: Guard.auth
        },
        {
            path: "/add-member-perfectmoney",
            component: AddMemberPMComponent,
            name: "addmember",
            beforeEnter: Guard.auth
        },

        {
            path: "/payment-invoice-status/:invoice_id",
            name: "payment-invoice-status",
            component: FundInvoiceComponent,
            beforeEnter: Guard.auth,
            meta: {
                title: "payment-invoice-status",
            }
        },
        {
            path: '/admin-setting-add-fund',
            component: AdminSettingAddFundComponent,
            name: 'adminsettingaddfund',
            beforeEnter: Guard.auth
        },
        {
            path: '/setting-add-fund-report',
            component: AdminSettingFundReportComponent,
            name: 'adminsettingfundreport',
            beforeEnter: Guard.auth
        },
		{
            path: '/all-transaction-ip-history',
            component: AllTransactionsIPReportComponent,
            name: 'alltransactionsipreport',
            beforeEnter: Guard.auth
        },
		{
            path: '/fake-topup-report',
            component: FakeTopupReportComponent,
            name: 'faketopupreport',
            beforeEnter: Guard.auth
        },
		{
            path: '/fake-withdraw-report',
            component: FakeWithdrawReportComponent,
            name: 'fakewithdrawreport',
            beforeEnter: Guard.auth
        },
        {
            path: '/daily-report',
            component: DailyReportComponent,
            name: 'dailyreport',
            beforeEnter: Guard.auth
        },
        {
            path: '/invalid-login-users',
            component: InvalidLoginUsersReportComponent,
            name: 'invalidloginusersreport',
            beforeEnter: Guard.auth
        },
        {
            path: '/security-dashboard',
            component: SecurityDashboardComponent,
            name: 'securitydashboard',
            beforeEnter: Guard.auth
		},
		{
			path: "/add_business_bv",
            component: AddBusinessBVComponent,
            name: "addbussinessbv",
            beforeEnter: Guard.auth
        },
        {
            path: "/add-remove-bussiness-upline-report",
            component: AddRemoveBussinessUplineReportComponent,
            name: "add-remove-bussiness-upline-report",
            beforeEnter: Guard.auth
        },

    ]
});