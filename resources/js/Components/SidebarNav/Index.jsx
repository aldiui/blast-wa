import React from "react";
import {
    Box,
    useColorModeValue,
    Drawer,
    DrawerContent,
    useDisclosure,
} from "@chakra-ui/react";
import { HomeIcon, UserGroupIcon, UserIcon } from "@heroicons/react/16/solid";
import SidebarContent from "./SidebarContent";
import MobileNav from "./MobileNav";

const SidebarNav = ({ children, auth }) => {
    const { isOpen, onOpen, onClose } = useDisclosure();

    const listNav = [
        { name: "Dashboard", icon: HomeIcon, href: "/" },
        { name: "Kelas", icon: UserGroupIcon, href: "/kelas" },
        { name: "Siswa", icon: UserIcon, href: "/siswa" },
    ];
    return (
        <Box
            w={"full"}
            minH="100vh"
            bg={useColorModeValue("gray.100", "gray.900")}
        >
            <SidebarContent
                listNav={listNav}
                onClose={() => onClose}
                display={{ base: "none", md: "block" }}
            />
            <Drawer
                autoFocus={false}
                isOpen={isOpen}
                placement="left"
                onClose={onClose}
                returnFocusOnClose={false}
                onOverlayClick={onClose}
                size="full"
            >
                <DrawerContent>
                    <SidebarContent
                        listNav={listNav}
                        auth={auth}
                        onClose={onClose}
                    />
                </DrawerContent>
            </Drawer>
            <MobileNav auth={auth} onOpen={onOpen} />
            <Box ml={{ base: 0, md: 60 }} p="4">
                {children}
            </Box>
        </Box>
    );
};

export default SidebarNav;
