<%@ Control Language="C#" AutoEventWireup="true" CodeBehind="ucLogin.ascx.cs" Inherits="Workout.App_UserControls.ucLogin" %>

<<%@ Register Src="~/App_UserControls/DimensionalUC.ascx" TagPrefix="uc" TagName="Dimensions" %>

<uc:Dimensions runat="server" ID="ucDimensions" />
<asp:Panel runat="server" ID="pnlContainer">
    <header>
        <span class="avatar"><asp:Image runat="server" ImageUrl="~/Content/images/avatar.jpg" meta:resourcekey="ImageResource1" /></span>
        <h1>Workout Log</h1>
        <p>Log In</p>
    </header>

    <footer>
        <ul class="icons">
            <li><asp:HyperLink runat="server" NavigateUrl="~/Account/Login.aspx" CssClass="fa-google" meta:resourcekey="HyperLinkResource1" /></li>
            <li><asp:HyperLink runat="server" NavigateUrl="~/Account/Login.aspx" CssClass="fa-facebook" meta:resourcekey="HyperLinkResource2" /></li>
        </ul>
    </footer>
</asp:Panel>