<%@ Page Title="Home" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="WorkoutLog._Default" %>
<%@ Import Namespace="WorkoutLog.Extensions" %>

<asp:Content ID="BodyContent" ContentPlaceHolderID="MainContent" runat="server">

    <asp:LoginView runat="server" ViewStateMode="Disabled">
        <AnonymousTemplate>
            <div class="jumbotron">
                <h1>Workout Log</h1>
                <p class="lead">Workout Log is a free tool that allows users to log, edit, and track their workouts and compare their progress with their peers. Training information and advice can also be provided by coaches to their team.</p>

                <asp:Button ID="RegisterButton" runat="server" Text="Register" CssClass="pure-button pure-button-primary" OnClick="RegisterButton_Click" />
            </div>
        </AnonymousTemplate>
        <LoggedInTemplate>
            <div class="jumbotron">
                <h2>Welcome back, <%= Context.User.Identity.GetFirstName() %>.</h2>
                <p class="lead">We're under development and will have features to you shortly. In the meantime, check for updates and information on our <asp:HyperLink runat="server" ID="AboutHyperLink" ViewStateMode="Disabled" NavigateUrl="About" CssClass="hyperlink">about page</asp:HyperLink>.</p>
            </div>
        </LoggedInTemplate>
    </asp:LoginView>

</asp:Content>
