import React, { useEffect } from "react";
import { Container, VStack, useToast } from "@chakra-ui/react";
import SidebarNav from "../Components/SidebarNav/Index";

const AdminLayout = ({ children, auth, sessions }) => {
  const toast = useToast();

  useEffect(() => {
    const displayMessage = (type, message) => {
      if (message) {
        toast({
          title: type,
          description: message,
          status: type.toLowerCase(),
          duration: 3000,
          isClosable: true,
          position: "top-right",
          variant: "left-accent",
        });
      }
    };

    displayMessage("Success", sessions?.success);
    displayMessage("Error", sessions?.error);
  }, [sessions, toast]);

  return (
    <VStack>
      <SidebarNav auth={auth}>
        <Container maxW="container.xl" mt={6} minH={"100vh"}>
          {children}
        </Container>
      </SidebarNav>
    </VStack>
  );
};

export default AdminLayout;
