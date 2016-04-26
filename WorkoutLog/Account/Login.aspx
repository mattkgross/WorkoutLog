<%@ Page Title="Log In" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Login.aspx.cs" Inherits="WorkoutLog.Account.Login" Async="true" %>

<%@ Register Src="~/Account/OpenAuthProviders.ascx" TagPrefix="uc" TagName="OpenAuthProviders" %>

<asp:Content runat="server" ID="BodyContent" ContentPlaceHolderID="MainContent">
    <h2><%: Title %></h2>

    <div class="row">
        <div class="col-md-12">
            <section id="loginForm">
                <div class="form-horizontal">
                    <h5>If you are a new user, please <asp:HyperLink runat="server" ID="RegisterHyperLink" ViewStateMode="Disabled" CssClass="hyperlink">register</asp:HyperLink>.</h5>
                    <hr />
                    <asp:PlaceHolder runat="server" ID="ErrorMessage" Visible="false">
                        <p class="text-danger">
                            <asp:Literal runat="server" ID="FailureText" />
                        </p>
                    </asp:PlaceHolder>
                    <div class="form-group">
                        <!--<asp:Label runat="server" AssociatedControlID="Email" CssClass="col-md-offset-3 col-md-2 control-label">Email</asp:Label>-->
                        <div class="col-md-12 text-center">
                            <asp:TextBox runat="server" ID="Email" CssClass="form-control align-center text-center" TextMode="Email" />
                            <asp:RequiredFieldValidator runat="server" ControlToValidate="Email"
                                CssClass="text-danger" ErrorMessage="The email field is required." />
                        </div>
                    </div>
                    <div class="form-group">
                        <!--<asp:Label runat="server" AssociatedControlID="Password" CssClass="col-md-offset-3 col-md-2 control-label">Password</asp:Label>-->
                        <div class="col-md-12 text-center">
                            <asp:TextBox runat="server" ID="Password" TextMode="Password" CssClass="form-control align-center text-center" />
                            <asp:RequiredFieldValidator runat="server" ControlToValidate="Password" CssClass="text-danger" ErrorMessage="The password field is required." />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="text-center checkbox">
                                <asp:CheckBox runat="server" ID="RememberMe" CssClass="checkbox-center" Checked="true" />
                                <asp:Label runat="server" AssociatedControlID="RememberMe" CssClass="checkbox-label-align">Remember me?</asp:Label>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <asp:Button runat="server" OnClick="LogIn" Text="Log in" CssClass="pure-button pure-button-primary" />
                        </div>
                    </div>
                </div>
                <p>
                    <%-- Enable this once you have account confirmation enabled for password reset functionality
                    <asp:HyperLink runat="server" ID="ForgotPasswordHyperLink" ViewStateMode="Disabled">Forgot your password?</asp:HyperLink>
                    --%>
                </p>
            </section>
        </div>

        <!--<div class="col-md-4">
            <section id="socialLoginForm">
                <uc:OpenAuthProviders runat="server" ID="OpenAuthLogin" />
            </section>
        </div>-->
    </div>
</asp:Content>
