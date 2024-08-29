import React from "react";
import { Head } from "@inertiajs/react";
import AdminLayout from "../../../Layouts/AdminLayout";

const Dashboard = ({ auth, sessions }) => {
  return (
    <AdminLayout auth={auth} sessions={sessions}>
      <Head title="Dashboard" />
    </AdminLayout>
  );
};

export default Dashboard;
