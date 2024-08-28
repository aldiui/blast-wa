import {
    Box,
    Button,
    Container,
    Flex,
    HStack,
    Icon,
    Img,
    Input,
    InputGroup,
    InputRightElement,
    SimpleGrid,
    Text,
    VStack,
} from "@chakra-ui/react";
import React from "react";
import { FaFacebook, FaInstagram, FaTiktok, FaTwitter } from "react-icons/fa";

const Footer = () => {
    const year = new Date().getFullYear();
    return (
        <VStack background={"gray.100"} w={"full"} p={8} mt={16}>
            <SimpleGrid
                maxW="container.xl"
                w={"full"}
                mb={10}
                columns={{ base: 1, xl: 3 }}
                spacing={10}
            >
                <Flex direction={"column"} gap={2} textAlign={"left"}>
                    <Img
                        src="/images/logo.png"
                        w={240}
                        mx={"auto"}
                        alt="Seetrip Logo"
                    ></Img>
                    <Text>Address: 60-49 Road 11378 Indonesia</Text>
                    <Text>Phone: 0812 3456 7890</Text>
                    <Text>Email: hello@seetrip.com</Text>
                </Flex>
                <Flex direction={"column"} gap={2} textAlign={"left"}>
                    <Text fontSize={"lg"} fontWeight={"bold"}>
                        Tentang SeeTrip
                    </Text>
                    <SimpleGrid gap={2} columns={2}>
                        <Text>Tentang Kami</Text>
                        <Text>Produk</Text>
                        <Text>Cara Pesan</Text>
                        <Text>Pembelian Paket Wisata</Text>
                        <Text>Pusat Bantuan</Text>
                        <Text>Online</Text>
                        <Text>TOS</Text>
                        <Text>QRIS Pembayaran</Text>
                    </SimpleGrid>
                </Flex>
                <Flex direction={"column"} gap={4} textAlign={"left"}>
                    <Text fontSize={"lg"} fontWeight={"bold"}>
                        Ikuti Kami Untuk Update Berita Terbaru
                    </Text>
                    <Text>
                        Dapatkan E-mail Baru tentang penawaran dan info menarik.
                    </Text>
                    <InputGroup size="md">
                        <Input
                            borderColor="blue.500"
                            p={2}
                            type="email"
                            placeholder="Email"
                        />
                        <InputRightElement width="auto">
                            <Button
                                size="md"
                                colorScheme="blue"
                                px={4}
                                borderLeftRadius={0}
                            >
                                Kirim
                            </Button>
                        </InputRightElement>
                    </InputGroup>
                    <Flex gap={2}>
                        <Button
                            p={2}
                            variant={"outline"}
                            shadow={"sm"}
                            borderRadius={"full"}
                            _hover={{ bg: "blue.600", color: "white" }}
                        >
                            <Icon as={FaFacebook} />
                        </Button>
                        <Button
                            p={2}
                            variant={"outline"}
                            shadow={"sm"}
                            borderRadius={"full"}
                            _hover={{ bg: "blue.600", color: "white" }}
                        >
                            <Icon as={FaTwitter} />
                        </Button>
                        <Button
                            p={2}
                            variant={"outline"}
                            shadow={"sm"}
                            borderRadius={"full"}
                            _hover={{ bg: "blue.600", color: "white" }}
                        >
                            <Icon as={FaInstagram} />
                        </Button>
                        <Button
                            p={2}
                            variant={"outline"}
                            shadow={"sm"}
                            borderRadius={"full"}
                            _hover={{ bg: "blue.600", color: "white" }}
                        >
                            <Icon as={FaTiktok} />
                        </Button>
                    </Flex>
                </Flex>
            </SimpleGrid>
            <Container maxW="container.xl">
                <Text textAlign={"center"}>
                    © {year} All rights reserved | This Platform is made with ❤️
                    <Text
                        as="span"
                        ml={1}
                        fontWeight={"bold"}
                        color={"blue.500"}
                    >
                        SeeTrip.id
                    </Text>
                </Text>
            </Container>
        </VStack>
    );
};

export default Footer;
