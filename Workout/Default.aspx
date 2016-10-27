<%@ Page Title="Home Page" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="Workout._Default" %>

<asp:Content ID="BodyContent" ContentPlaceHolderID="MainContent" runat="server">
    <header>
        <span class="avatar"><asp:Image runat="server" ImageUrl="~/Content/images/avatar.jpg" AlternateText="Logged In User" /></span>
        <h1>Logged In User</h1>
        <p>Senior Psychonautics Engineer</p>
    </header>
</asp:Content>
