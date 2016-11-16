<%@ Page Title="" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Login.aspx.cs" Inherits="Workout.Account.Login" %>

<%@ Register Src="~/App_UserControls/ucLogin.ascx" TagName="Login" TagPrefix="uc" %>

<asp:Content ID="Content1" ContentPlaceHolderID="MainHead" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="MainContent" runat="server">
    <uc:Login runat="server" ID="ucLogin" />
</asp:Content>
