<%@ Page Title="Team" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Team.aspx.cs" Inherits="WorkoutLog.Team.Team" %>
<%@ Import Namespace="WorkoutLog.Models" %>
<%@ Register TagPrefix="uc" TagName="uc_CreateTeam" Src="~/Team/uc/uc_CreateTeam.ascx" %>

<asp:Content ID="Content1" ContentPlaceHolderID="MainContent" runat="server">
    <script src="<%=ResolveUrl("~/Scripts/WebForms/Team.js") %>" type="text/javascript"></script>
    <div class="team-container row">
        <div class="col-md-2">
            <!-- New Team Modal UC -->
            <uc:uc_CreateTeam id="uc_CreateTeam" runat="server"></uc:uc_CreateTeam>
            <!-- End New Team Modal -->
        </div>
        <div class="col-md-8">
            <h1 class="text-center"><%= ((WorkoutLog.Models.Team)Session["Team"]).Name %></h1>
        </div>
        <div class="col-md-2">
            <!-- Join Team UC -->

            <!-- End Join Team UC -->
        </div>
    </div>
</asp:Content>