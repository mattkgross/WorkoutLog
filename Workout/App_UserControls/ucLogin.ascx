<%@ Control Language="C#" AutoEventWireup="true" CodeBehind="ucLogin.ascx.cs" Inherits="Workout.App_UserControls.ucLogin" %>

<asp:Panel runat="server" ID="pnlContainer">
    <header>
        <span class="avatar"><asp:Image runat="server" ImageUrl="~/Content/images/avatar.jpg" meta:resourcekey="ImageResource1" /></span>
        <h1>Workout Log</h1>
        <p>Log In</p>
    </header>

    <footer>
        <ul class="icons">
            <li><asp:LinkButton runat="server" ID="lnkGoogle" CssClass="fa-google" meta:resourcekey="HyperLinkResource1" OnClick="lnkGoogle_Click" /></li>
            <li><asp:LinkButton runat="server" ID="lnkFacebook" CssClass="fa-facebook" meta:resourcekey="HyperLinkResource2" OnClick="lnkFacebook_Click" /></li>
        </ul>
    </footer>
</asp:Panel>