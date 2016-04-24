<%@ Page Title="Home" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="WorkoutLog._Default" %>

<asp:Content ID="BodyContent" ContentPlaceHolderID="MainContent" runat="server">

    <div class="jumbotron">
        <h1>Workout Log</h1>
        <p class="lead">Workout Log is a free tool that allows users to log, edit, and track their workouts and compare their progress with their peers. Training information and advice can also be provided by coaches to their team.</p>

        <asp:Button ID="RegisterButton" runat="server" Text="Register" CssClass="pure-button pure-button-primary" OnClick="RegisterButton_Click" />
    </div>

</asp:Content>
