using Microsoft.Data.SqlClient;

namespace ECommers.Utill
{
    public class DBConnection
    {
        private SqlConnection connection;

        public DBConnection()
        {
            var Constring = new ConfigurationBuilder().AddJsonFile("appsettings.json").Build().GetSection("ConnectionStrings")["$con"];
            connection = new SqlConnection(Constring);
        }
        public SqlConnection GetConn()
        {
            return connection;
        }
        public void connOpen()
        {

            connection.Open();

        }
        public void connClose()
        {
            if (connection.State == System.Data.ConnectionState.Open)
            {
                connection.Close();
            }
    }

    internal bool conIsOPen()
        {
            throw new NotImplementedException();
        }
    }
    }
