<%@ Page Title="Team" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Team.aspx.cs" Inherits="WorkoutLog.Team.Team" %>
<%@ Register TagPrefix="uc" TagName="uc_CreateTeam" Src="~/Team/uc/uc_CreateTeam.ascx" %>

<asp:Content ID="Content1" ContentPlaceHolderID="MainContent" runat="server">
    <script src="<%=ResolveUrl("~/Scripts/WebForms/Team.js") %>" type="text/javascript"></script>
    <div class="row">
        <div class="team-container col-md-12">
            <!-- New Team Modal UC -->
            <uc:uc_CreateTeam id="uc_CreateTeam" runat="server"></uc:uc_CreateTeam>
            <!-- End New Team Modal -->
        </div>
    </div>
</asp:Content>