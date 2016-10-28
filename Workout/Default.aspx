<%@ Page Title="" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="Workout._Default" %>

<%@ Register Src="~/App_UserControls/ucLogin.ascx" TagName="Login" TagPrefix="uc" %>

<asp:Content ID="HeadContent" ContentPlaceHolderID="MainHead" runat="server">
    <link rel="stylesheet" href="css/Default.min.css" type="text/css" />
</asp:Content>
<asp:Content ID="BodyContent" ContentPlaceHolderID="MainContent" runat="server">
    <uc:Login runat="server" ID="ucLogin" />
    <%--<asp:LoginView runat="server" ViewStateMode="Disabled">
        <AnonymousTemplate>
            <uc:Login runat="server" ID="ucLogin" />
        </AnonymousTemplate>
        <LoggedInTemplate>

        </LoggedInTemplate>
    </asp:LoginView>--%>
</asp:Content>
