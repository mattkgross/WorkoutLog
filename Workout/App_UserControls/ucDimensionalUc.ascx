<%@ Control Language="C#" AutoEventWireup="true" CodeBehind="ucDimensionalUc.ascx.cs" Inherits="Workout.App_UserControls.ucDimensionalUc" %>

<script>
    $(document).ready(function () {
        $("#<%= clientScreenHeight.ClientID %>").val($(screen).width());
        $("#<%= clientScreenWidth.ClientID %>").val($(screen).height());
    });
</script>
<asp:HiddenField runat="server" ID="clientScreenHeight" EnableViewState="false" />
<asp:HiddenField runat="server" ID="clientScreenWidth" EnableViewState="false" />