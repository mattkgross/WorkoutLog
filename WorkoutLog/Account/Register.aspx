﻿<%@ Page Title="Register" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Register.aspx.cs" Inherits="WorkoutLog.Account.Register" %>

<asp:Content runat="server" ID="BodyContent" ContentPlaceHolderID="MainContent">
    <h2><%: Title %></h2>

    <div class="form-horizontal">
        <h5>Create a new account.</h5>
        <p class="text-danger">
        <asp:Literal runat="server" ID="ErrorMessage" />
        </p>
        <hr />
        <!--<asp:ValidationSummary runat="server" CssClass="text-danger" />-->
        <div class="form-group">
            <asp:Label runat="server" AssociatedControlID="FirstName" CssClass="col-md-offset-3 col-md-2 control-label">First Name</asp:Label>
            <div class="col-md-7">
                <asp:TextBox runat="server" ID="FirstName" TextMode="SingleLine" CssClass="form-control" />
                <asp:RequiredFieldValidator runat="server" ID="rfvFirstName" ControlToValidate="FirstName"
                    CssClass="text-danger" ErrorMessage="Your first name is required." />
            </div>
        </div>
        <div class="form-group">
            <asp:Label runat="server" AssociatedControlID="LastName" CssClass="col-md-offset-3 col-md-2 control-label">Last Name</asp:Label>
            <div class="col-md-7">
                <asp:TextBox runat="server" ID="LastName" TextMode="SingleLine" CssClass="form-control" />
                <asp:RequiredFieldValidator runat="server" ID="rfvLastName" ControlToValidate="LastName"
                    CssClass="text-danger" ErrorMessage="Your last name is required." />
            </div>
        </div>
        <div class="form-group">
            <asp:Label runat="server" AssociatedControlID="Email" CssClass="col-md-offset-3 col-md-2 control-label">Email</asp:Label>
            <div class="col-md-7">
                <asp:TextBox runat="server" ID="Email" CssClass="form-control" TextMode="Email" />
                <asp:RequiredFieldValidator runat="server" ID="rfvEmail" ControlToValidate="Email"
                    CssClass="text-danger" ErrorMessage="Your email is required." />
            </div>
        </div>
        <div class="form-group">
            <asp:Label runat="server" AssociatedControlID="Password" CssClass="col-md-offset-3 col-md-2 control-label">Password</asp:Label>
            <div class="col-md-7">
                <asp:TextBox runat="server" ID="Password" TextMode="Password" CssClass="form-control" />
                <asp:RequiredFieldValidator runat="server" ID="rfvPassword" ControlToValidate="Password"
                    CssClass="text-danger" ErrorMessage="A password is required." />
            </div>
        </div>
        <div class="form-group">
            <asp:Label runat="server" AssociatedControlID="ConfirmPassword" CssClass="col-md-offset-3 col-md-2 control-label">Confirm Password</asp:Label>
            <div class="col-md-7">
                <asp:TextBox runat="server" ID="ConfirmPassword" TextMode="Password" CssClass="form-control" />
                <asp:RequiredFieldValidator runat="server" ID="rfvConfirmPassword" ControlToValidate="ConfirmPassword"
                    CssClass="text-danger" Display="Dynamic" ErrorMessage="You must confirm your password." />
                <asp:CompareValidator runat="server" ControlToCompare="Password" ControlToValidate="ConfirmPassword"
                    CssClass="text-danger" Display="Dynamic" ErrorMessage="The password and confirmation password do not match." />
            </div>
        </div>
        <br />
        <div class="form-group">
            <div class="col-md-12 text-center">
                <asp:Button runat="server" OnClick="CreateUser_Click" Text="Register" CssClass="pure-button pure-button-primary" />
            </div>
        </div>
    </div>
</asp:Content>
