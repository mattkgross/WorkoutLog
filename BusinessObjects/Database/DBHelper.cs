using System;
using System.Collections.Generic;
using System.Linq;
using System.Data;
using System.Data.SqlClient;

namespace BusinessObjects.Database
{
    public static class DBHelper
    {
        // Dev connection.
        #if DEBUG
        private static string defaultConnectionString = @"Data Source=(localdb)\MSSQLLocalDB;Initial Catalog=WorkoutLog;Integrated Security=True;Connect Timeout=30;Encrypt=False;TrustServerCertificate=True;ApplicationIntent=ReadWrite;MultiSubnetFailover=False";
        // Prod connection.
        #else
        private static string defaultConnectionString = "";
        #endif

        /// <summary>
        /// 
        /// </summary>
        /// <param name="PROC_NAME">Name of the stored procedure to run.</param>
        /// <param name="parameters">List of parameter values (maximum iof 26).</param>
        /// <returns></returns>
        public static DataTable ExecuteProcedure(string PROC_NAME, params object[] parameters)
        {
            try
            {
                CheckParameters(parameters);
                DataTable a = new DataTable();
                List<SqlParameter> filters = new List<SqlParameter>();

                string query = "EXEC " + PROC_NAME;

                bool first = true;
                char pname = 'a';
                foreach(object parameter in parameters)
                {
                    string name = string.Format("@{0}", pname++);
                    filters.Add(new SqlParameter(name, parameter));
                    query += (first ? " " : ", ") + (name);
                    first = false;
                }

                a = Query(query, filters);
                return a;
            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

        public static DataTable ExecuteQuery(string query, params object[] parameters)
        {
            try
            {
                CheckParameters(parameters);
                DataTable a = new DataTable();
                List<SqlParameter> filters = new List<SqlParameter>();

                char pname = 'a';
                foreach (object parameter in parameters)
                {
                    string name = string.Format("@{0}", pname++);
                    filters.Add(new SqlParameter(name, parameter));
                }

                a = Query(query, filters);
                return a;
            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

        public static int ExecuteNonQuery(string query, params object[] parameters)
        {
            try
            {
                CheckParameters(parameters);
                List<SqlParameter> filters = new List<SqlParameter>();

                char pname = 'a';
                foreach (object parameter in parameters)
                {
                    string name = string.Format("@{0}", pname++);
                    filters.Add(new SqlParameter(name, parameter));
                }

                return NonQuery(query, filters);
            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

        public static object ExecuteScalar(string query, params object[] parameters)
        {
            try
            {
                CheckParameters(parameters);
                List<SqlParameter> filters = new List<SqlParameter>();

                char pname = 'a';
                foreach (object parameter in parameters)
                {
                    string name = string.Format("@{0}", pname++);
                    filters.Add(new SqlParameter(name, parameter));
                }

                return Scalar(query, filters);
            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

        #region Private Methods

        private static DataTable Query(String query, IList<SqlParameter> parameters)
        {
            try
            {
                DataTable dt = new DataTable();
                SqlConnection connection = new SqlConnection(defaultConnectionString);
                SqlCommand command = new SqlCommand();
                SqlDataAdapter da;
                try
                {
                    command.Connection = connection;
                    command.CommandText = query;
                    if (parameters != null)
                    {
                        command.Parameters.AddRange(parameters.ToArray());
                    }
                    da = new SqlDataAdapter(command);
                    da.Fill(dt);
                }
                finally
                {
                    if (connection != null)
                        connection.Close();
                }
                return dt;
            }
            catch (Exception)
            {
                throw;
            }

        }

        private static int NonQuery(string query, IList<SqlParameter> parametros)
        {
            try
            {
                DataSet dt = new DataSet();
                SqlConnection connection = new SqlConnection(defaultConnectionString);
                SqlCommand command = new SqlCommand();

                try
                {
                    connection.Open();
                    command.Connection = connection;
                    command.CommandText = query;
                    command.Parameters.AddRange(parametros.ToArray());
                    return command.ExecuteNonQuery();

                }
                finally
                {
                    if (connection != null)
                        connection.Close();
                }

            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

        private static object Scalar(string query, List<SqlParameter> parametros)
        {
            try
            {
                DataSet dt = new DataSet();
                SqlConnection connection = new SqlConnection(defaultConnectionString);
                SqlCommand command = new SqlCommand();

                try
                {
                    connection.Open();
                    command.Connection = connection;
                    command.CommandText = query;
                    command.Parameters.AddRange(parametros.ToArray());
                    return command.ExecuteScalar();

                }
                finally
                {
                    if (connection != null)
                        connection.Close();
                }

            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

        private static void CheckParameters(object[] parameters)
        {
            // TODO: Allow any number of parameters.
            // Max is 26 for now with the current parameter naming scheme. Good enough for now.
            if (parameters.Length > 26)
                throw new ArgumentException("Only 26 parameters are supported.");
        }

        #endregion
    }
}
